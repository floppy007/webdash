# Docker-Networking und Erreichbarkeit in `webdash`

## Kurzfassung

Wenn `webdash` selbst in einem Docker-Container laeuft, funktionieren automatisch erkannte App-Links nicht in jedem Setup sauber. Das betrifft vor allem:

- Container hinter Reverse Proxy
- Container ohne `ports:`-Mapping nach aussen
- Setups mit Domain-basiertem Routing statt `HOST:PORT`
- Setups, in denen `WEBDASH_HOST_IP` aus dem Container heraus oder fuer den Browser nicht gueltig ist

Der aktuelle Code ist inzwischen robuster, aber die Grundgrenze bleibt bestehen: Docker-Portdaten sind nicht automatisch die echte Benutzer-URL.

## Was `webdash` aktuell macht

Im Docker-Modus wird die URL fuer einen erkannten Container in `.dashboard/app.php` aufgebaut:

- Docker-Modus-Erkennung und Host-IP: `.dashboard/app.php`
- Container-Erkennung und URL-Bau: `.dashboard/app.php`

Die Logik ist aktuell:

1. Docker-Container ueber `/var/run/docker.sock` lesen
2. Zuerst explizite URL-Informationen verwenden:
   - `webdash.url`
   - `webdash.host`, `webdash.scheme`, `webdash.path`, `webdash.port`
   - erkannte Traefik-Labels
3. Erst danach auf veroefentlichte Host-Ports zurueckfallen
4. Nicht veroefentlichte interne Ports nur verwenden, wenn `WEBDASH_DOCKER_ALLOW_PRIVATE_PORTS=true`
5. Den Status je nach `WEBDASH_DOCKER_HEALTH_MODE` bestimmen:
   - `state` => Docker-State
   - `http` => echter HTTP-Check auf die aufgeloeste URL
   - `off` => nur State, ohne Reachability-Pruefung

## Warum es in Docker haeufig schiefgeht

### 1. `WEBDASH_HOST_IP` ist nicht automatisch die richtige Zieladresse

`webdash` verwendet im Docker-Modus:

```php
$dockerHostIp = getenv('WEBDASH_HOST_IP') ?: ($_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost');
```

Das ist fuer viele Setups zu grob:

- `HTTP_HOST` ist oft die Adresse von **webdash selbst**, nicht die der Ziel-App
- bei Reverse Proxy ist die App eventuell nur unter einer **Domain** erreichbar, nicht ueber `HOST:PORT`
- bei NAT/Hairpin/Firewall kann die Host-IP aus Browser- oder Container-Sicht ungueltig sein

### 2. Port-Mapping ist nicht gleich gueltige Aufruf-URL

`discoverDockerContainers()` baut bevorzugt URLs wie:

- `http://HOST:PublicPort/`
- und nur optional als Fallback `http://HOST:PrivatePort/`

Das funktioniert nur, wenn:

- der Port wirklich auf dem Host veroeffentlicht ist
- der Port aus dem Client-Netz erreichbar ist
- keine Domain-/Path-basierte Weiterleitung vorgeschaltet ist

Typische Gegenbeispiele:

- App nur intern im Docker-Netz erreichbar
- App laeuft nur hinter Traefik / Nginx Proxy Manager / Caddy
- Container expose-t nur intern, aber hat kein `ports:` nach aussen
- die App ist unter `https://app.domain.tld/` erreichbar, nicht unter `http://HOST:3000/`

### 3. Reverse-Proxy-Setups koennen nicht automatisch erraten werden

Wenn mehrere Apps ueber einen zentralen Proxy laufen, ist die korrekte Ziel-URL meist:

- eine eigene Domain
- oder ein bestimmter Pfad

Das kann `webdash` aus Docker-Portdaten nicht herleiten. Docker kennt das Port-Mapping, aber nicht die finale externe URL des Proxys.

### 4. Aus Container-Sicht und aus Browser-Sicht gelten unterschiedliche Netzwege

Selbst wenn `webdash` spaeter einen HTTP-Check machen wuerde, waere das nicht automatisch dasselbe wie die Erreichbarkeit fuer den Benutzer im Browser:

- aus dem `webdash`-Container kann `host.docker.internal` eventuell funktionieren
- fuer den Browser des Benutzers ist dieselbe Adresse oft unbrauchbar
- umgekehrt kann eine externe Domain im Browser funktionieren, aber aus dem Container nicht aufloesbar sein

## Was bei dir wahrscheinlich passiert

Deine Beschreibung passt sehr gut zu diesem Muster:

- `webdash` laeuft im Container
- die andere App laeuft ebenfalls im Container oder hinter einem Proxy
- `webdash` baut daraus eine `HOST:PORT`-URL
- diese URL ist nicht der echte, von aussen gueltige Einstiegspunkt
- Ergebnis: Klick fuehrt ins Leere oder zeigt Fehler

Falls du mit "pingen" den Status meinst:

- mit `WEBDASH_DOCKER_HEALTH_MODE=http` gibt es jetzt einen echten `HEAD`/HTTP-Check ueber cURL
- mit `state` bleibt das Verhalten konservativ beim Docker-State
- manuelle Links sind weiterhin robust, wenn die finale Ziel-URL feststeht

## Was heute zuverlaessig funktioniert

### Variante A: `webdash.url` pro Container setzen

Das ist die sauberste Loesung fuer Container, die nicht direkt ueber simples `HOST:PORT` erreichbar sind.

Beispiel:

```yaml
services:
  app:
    image: nginx:alpine
    labels:
      webdash.name: "Meine App"
      webdash.url: "https://app.example.com"
```

Mit Platzhalter:

```yaml
labels:
  webdash.url: "http://{HOST_IP}:3000"
```

Das funktioniert dann sauber, wenn `WEBDASH_HOST_IP` wirklich die Zieladresse fuer den Browser ist.

### Variante B: Manuelle Links im Admin-Panel

Wenn die App hinter einem Reverse Proxy oder unter einer Domain laeuft, sind manuelle Links oft die bessere Loesung.

Vorteile:

- finale URL wird exakt gesetzt
- kein Raten ueber Docker-Portdaten
- bei nicht erreichbarem Check wird der Link trotzdem angezeigt

### Variante C: Nur einfache `HOST:PORT`-Setups automatisch erkennen

Auto-Erkennung ist sinnvoll, wenn:

- jede App einen echten `ports:`-Publish auf dem Host hat
- keine Proxy-Domain dazwischenliegt
- `WEBDASH_HOST_IP` korrekt gesetzt ist

Beispiel:

```yaml
services:
  webdash:
    image: floppy001/webdash:latest
    environment:
      WEBDASH_DOCKER_MODE: "true"
      WEBDASH_HOST_IP: "192.168.1.50"
      WEBDASH_DOCKER_HEALTH_MODE: "http"
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock:ro
```

## Was man im Code verbessern sollte

Bereits umgesetzt:

- explizite Label fuer Host, Scheme, Path und Port
- Traefik-Host-Erkennung aus Router-Labels
- nur veroefentlichte Ports als Standard-Fallback
- optionaler HTTP-Reachability-Check ueber `WEBDASH_DOCKER_HEALTH_MODE=http`
- harte Private-Port-Fallbacks nur noch per Opt-in

Weiterhin sinnvoll fuer spaetere Ausbaustufen:

- weitere Proxy-Label-Parser ausser Traefik
- separates Anzeigen von `docker_state` und Reachability im UI
- eigene Admin-Einstellung fuer den Docker-Health-Modus

## Praktische Empfehlung fuer dein Setup

Wenn deine Apps "hinter Docker" oder "hinter einem Proxy" laufen, arbeite vorerst so:

1. Fuer jede App mit eigener Domain oder Proxy-Route `webdash.url` setzen.
2. Falls die URL dynamisch oder individueller ist, die App als manuellen Link pflegen.
3. `WEBDASH_HOST_IP` nur dann verwenden, wenn die App wirklich ueber `IP:PORT` im Browser erreichbar ist.
4. Auto-Port-Erkennung nur fuer simple Homelab- oder Lab-Setups nutzen.
5. `WEBDASH_DOCKER_ALLOW_PRIVATE_PORTS` nur aktivieren, wenn du die Netzsicht bewusst so brauchst.

## Relevante Code-Stellen

- Docker-Modus aktivieren: `.dashboard/app.php`
- Docker-Container erkennen und URL bauen: `.dashboard/app.php`
- HTTP-Health-Check fuer Docker-Container, manuelle Links und Verzeichnis-Scan: `.dashboard/app.php`
- Verzeichnis-Scan mit lokalem HTTP-Check: `.dashboard/app.php`

## Fazit

Das Problem ist kein einzelner "Ping-Bug", sondern ein Architekturthema:

- Docker-Portdaten beschreiben nicht immer die echte Benutzer-URL
- Container-Netzsicht und Browser-Netzsicht sind nicht dasselbe
- Domain-/Proxy-basierte Setups koennen nicht zuverlaessig aus `ports` erraten werden

Fuer solche Setups funktioniert `webdash` jetzt deutlich sauberer, aber die beste Loesung bleibt weiterhin: die echte Ziel-URL explizit ueber Labels oder manuelle Links angeben.
