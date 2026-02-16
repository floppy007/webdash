<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset=".dashboard/app-logo-dark.png">
    <source media="(prefers-color-scheme: light)" srcset=".dashboard/app-logo-light.png">
    <img src=".dashboard/app-logo-light.png" alt="webdash" height="120">
  </picture>
</p>

# webdash

Ein schlankes, selbstgehostetes Server-Dashboard als einzelne PHP-Datei — nativ oder als Docker-Container.

A lightweight, self-hosted server dashboard in a single PHP file — native or as a Docker container.

---

## Deutsch

### Was ist webdash?

webdash zeigt alle Webanwendungen und Dienste auf deinem Server in einer übersichtlichen Startseite. Im **Docker-Modus** werden laufende Container automatisch erkannt, im **Normal-Modus** scannt webdash ein Verzeichnis nach Projekten. Zusätzlich können beliebige URLs manuell hinzugefügt werden. Jede Kachel zeigt den Live-Status (Online/Offline/Fehler) und ist direkt anklickbar.

**Wofür?** Du hast mehrere Webanwendungen, Container oder Dienste auf einem Server und willst eine zentrale Übersicht — statt dir IPs und Ports zu merken, öffnest du einfach webdash. Ideal als Startseite für Homeserver, Unraid, NAS oder Entwicklungsumgebungen.

### Features

- **Docker-Modus** — Container-Erkennung über Docker Socket, anpassbar per Labels, ein-/ausblendbar per Toggle
- **Verzeichnis-Scan** — Erkennt Webanwendungen automatisch mit Live-Status, ein-/ausblendbar per Toggle
- **Manuelle Links** — Eigene URLs hinzufügen mit Health-Check
- **Projekt-Logos** — Separate Logos für Dark- und Light-Mode pro Projekt (Drag & Drop Upload)
- **Hintergrundbild** — Preset-Wallpapers (Hell/Dunkel) oder eigenes Bild hochladen, mit Helligkeits- und Glaseffekt-Reglern
- **System-Monitoring** — CPU, RAM, Festplatte, Dienste-Status
- **Benutzerverwaltung** — Mehrere Admin-Benutzer anlegen, bearbeiten, löschen (gespeichert in config.json)
- **SMTP E-Mail** — E-Mail-Versand konfigurierbar, Passwort-Zurücksetzung per E-Mail
- **Admin-Panel** — Projekte bearbeiten, Benutzer verwalten, Logos, SMTP-Config
- **Setup-Wizard** — Geführte Ersteinrichtung (Docker: vereinfacht, Bare-Metal: vollständig)
- **Auto-Update** — Update direkt im Admin-Panel
- **Google-Suche** — Optionale Google-Suchleiste im User-View, aktivierbar in den Einstellungen
- **Dark/Light Theme** — Umschaltbar
- **Zweisprachig** — Deutsch & Englisch
- **Unraid-kompatibel** — XML-Template für Unraid Community Apps enthalten

### Installation

#### Variante 1: Docker (empfohlen für Unraid / NAS)

```yaml
services:
  webdash:
    image: floppy001/webdash:latest
    container_name: webdash
    ports:
      - "8080:80"
    environment:
      - WEBDASH_DOCKER_MODE=true
      #- WEBDASH_HOST_IP=192.168.1.100  # Optional: nur bei Reverse-Proxy
      #- WEBDASH_HOSTNAME=mein-server    # Optional: Anzeigename statt Container-ID
      #- WEBDASH_SCAN_DIR=/sites         # Optional: zusätzlich Verzeichnis scannen
    volumes:
      - webdash-data:/var/www/html/.dashboard
      - /var/run/docker.sock:/var/run/docker.sock:ro
      #- /mnt/user/appdata/sites:/sites:ro # Optional: Host-Verzeichnis mit Webanwendungen
    restart: unless-stopped

volumes:
  webdash-data:
```

```bash
docker compose up -d
```

Dashboard öffnen: `http://SERVER-IP:8080`

##### Docker Environment-Variablen

| Variable | Beschreibung | Pflicht |
|---|---|---|
| `WEBDASH_DOCKER_MODE` | `true` aktiviert Docker-Modus (Container statt Verzeichnisse) | Ja |
| `WEBDASH_HOST_IP` | Host-IP für Container-URLs (Default: `HTTP_HOST`) | Optional |
| `WEBDASH_HOSTNAME` | Anzeigename im Dashboard (Default: System-Hostname) | Optional |
| `WEBDASH_SCAN_DIR` | Zusätzliches Verzeichnis scannen (+ Volume-Mount nötig) | Optional |

##### Container per Label anpassen

```bash
docker run -d \
  --label webdash.name="Meine App" \
  --label webdash.icon="🎮" \
  --label webdash.description="Beschreibung der App" \
  --label webdash.url="http://{HOST_IP}:3000" \
  --label webdash.hidden=true \
  nginx
```

| Label | Beschreibung |
|---|---|
| `webdash.name` | Anzeigename (statt Container-Name) |
| `webdash.icon` | Emoji-Icon (Default: 🐳) |
| `webdash.description` | Beschreibungstext |
| `webdash.url` | Eigene URL (`{HOST_IP}` wird ersetzt) |
| `webdash.hidden` | `true` = Container im Dashboard ausblenden |

#### Variante 2: Installations-Wizard (Bare-Metal)

1. `install.php` auf den Server herunterladen:
   ```bash
   curl -L https://raw.githubusercontent.com/floppy007/webdash/main/install.php -o /var/www/install.php
   ```
2. Im Browser öffnen: `http://dein-server/install.php`
3. Der Wizard prüft die Voraussetzungen und installiert webdash automatisch
4. **`install.php` nach der Installation löschen!**

#### Variante 3: Manuelle Installation

```bash
mkdir -p /var/www/.dashboard
curl -L https://raw.githubusercontent.com/floppy007/webdash/main/index.php -o /var/www/index.php
curl -L https://raw.githubusercontent.com/floppy007/webdash/main/.dashboard/app.php -o /var/www/.dashboard/app.php
curl -L https://raw.githubusercontent.com/floppy007/webdash/main/.htaccess -o /var/www/.htaccess

chown -R www-data:www-data /var/www/.dashboard
```

### Ersteinrichtung

Beim ersten Aufruf erscheint der Setup-Wizard:

Beim ersten Aufruf ein **Admin-Passwort** festlegen. Die Konfiguration wird in `.dashboard/config.json` gespeichert und kann jederzeit im Admin-Panel geändert werden.

### Voraussetzungen

**Docker:** Docker + Docker Compose

**Bare-Metal:**
- PHP 8.1 oder höher
- Apache mit `mod_rewrite`
- PHP-Extensions: `curl`
- Schreibrechte für `.dashboard/`

### Hinweise (Bare-Metal)

- `index.php` muss im DocumentRoot (oder VirtualHost-Verzeichnis) liegen
- `AllowOverride All` muss für das Verzeichnis aktiviert sein
- `mod_rewrite` muss aktiviert sein (`a2enmod rewrite && systemctl restart apache2`)

### Reverse Proxy (HTTPS)

webdash läuft bewusst nur über HTTP — für den internen Einsatz auf Homeservern und im LAN reicht das.
Für externen Zugriff mit HTTPS empfehlen wir einen Reverse Proxy (z.B. nginx, Caddy, Traefik).
webdash erkennt den `X-Forwarded-Proto`-Header automatisch und generiert Links mit dem richtigen Protokoll.

Beispiel nginx:
```nginx
server {
    listen 443 ssl;
    server_name dash.example.com;

    ssl_certificate     /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### Update

**Docker:** Neues Image pullen und Container neu starten:
```bash
docker compose pull && docker compose up -d
```

**Bare-Metal:** Im Admin-Panel unter **Einstellungen → Update prüfen** klicken. Die alten Dateien werden als `.bak` gesichert.

---

## English

### What is webdash?

webdash shows all web applications and services on your server in a clean start page. In **Docker mode**, running containers are discovered automatically. In **normal mode**, webdash scans a directory for projects. You can also add any URL manually. Each tile shows the live status (Online/Offline/Error) and is directly clickable.

**Why?** You have multiple web apps, containers, or services on a server and want a central overview — instead of remembering IPs and ports, just open webdash. Ideal as a start page for home servers, Unraid, NAS, or development environments.

### Features

- **Docker Mode** — Container discovery via Docker socket, customizable with labels, toggleable visibility
- **Directory Scan** — Auto-detects web applications with live status, toggleable visibility
- **Manual Links** — Add custom URLs with health checks
- **Project Logos** — Separate logos for dark and light mode per project (drag & drop upload)
- **Background Image** — Preset wallpapers (light/dark) or custom upload, with brightness and glass effect sliders
- **System Monitoring** — CPU, RAM, disk, service status
- **User Management** — Create, edit, delete multiple admin users (stored in config.json)
- **SMTP Email** — Configurable email sending, password reset via email
- **Admin Panel** — Edit projects, manage users, logos, SMTP config
- **Setup Wizard** — Guided first-run setup (Docker: simplified, bare-metal: full)
- **Auto-Update** — Update directly from the admin panel
- **Google Search** — Optional Google search bar in user view, can be enabled in settings
- **Dark/Light Theme** — Toggleable
- **Bilingual** — German & English
- **Unraid-compatible** — XML template for Unraid Community Apps included

### Installation

#### Option 1: Docker (recommended for Unraid / NAS)

```yaml
services:
  webdash:
    image: floppy001/webdash:latest
    container_name: webdash
    ports:
      - "8080:80"
    environment:
      - WEBDASH_DOCKER_MODE=true
      #- WEBDASH_HOST_IP=192.168.1.100  # Optional: only for reverse proxy
      #- WEBDASH_HOSTNAME=my-server      # Optional: display name instead of container ID
      #- WEBDASH_SCAN_DIR=/sites         # Optional: also scan a directory
    volumes:
      - webdash-data:/var/www/html/.dashboard
      - /var/run/docker.sock:/var/run/docker.sock:ro
      #- /mnt/user/appdata/sites:/sites:ro # Optional: host directory with web apps
    restart: unless-stopped

volumes:
  webdash-data:
```

```bash
docker compose up -d
```

Open dashboard: `http://SERVER-IP:8080`

##### Docker Environment Variables

| Variable | Description | Required |
|---|---|---|
| `WEBDASH_DOCKER_MODE` | `true` enables Docker mode (containers instead of directories) | Yes |
| `WEBDASH_HOST_IP` | Host IP for container URLs (default: `HTTP_HOST`) | Optional |
| `WEBDASH_HOSTNAME` | Display name in dashboard (default: system hostname) | Optional |
| `WEBDASH_SCAN_DIR` | Also scan a directory for web apps (requires volume mount) | Optional |

##### Customize Containers with Labels

```bash
docker run -d \
  --label webdash.name="My App" \
  --label webdash.icon="🎮" \
  --label webdash.description="App description" \
  --label webdash.url="http://{HOST_IP}:3000" \
  --label webdash.hidden=true \
  nginx
```

| Label | Description |
|---|---|
| `webdash.name` | Display name (instead of container name) |
| `webdash.icon` | Emoji icon (default: 🐳) |
| `webdash.description` | Description text |
| `webdash.url` | Custom URL (`{HOST_IP}` is replaced) |
| `webdash.hidden` | `true` = hide container from dashboard |

#### Option 2: Installation Wizard (bare-metal)

1. Download `install.php` to your server:
   ```bash
   curl -L https://raw.githubusercontent.com/floppy007/webdash/main/install.php -o /var/www/install.php
   ```
2. Open in browser: `http://your-server/install.php`
3. The wizard checks requirements and installs webdash automatically
4. **Delete `install.php` after installation!**

#### Option 3: Manual Installation

```bash
mkdir -p /var/www/.dashboard
curl -L https://raw.githubusercontent.com/floppy007/webdash/main/index.php -o /var/www/index.php
curl -L https://raw.githubusercontent.com/floppy007/webdash/main/.dashboard/app.php -o /var/www/.dashboard/app.php
curl -L https://raw.githubusercontent.com/floppy007/webdash/main/.htaccess -o /var/www/.htaccess

chown -R www-data:www-data /var/www/.dashboard
```

### First-Run Setup

On first visit, set an **admin password**. Configuration is stored in `.dashboard/config.json` and can be changed at any time in the admin panel.

### Requirements

**Docker:** Docker + Docker Compose

**Bare-metal:**
- PHP 8.1 or higher
- Apache with `mod_rewrite`
- PHP extensions: `curl`
- Write permissions for `.dashboard/`

### Hints (bare-metal)

- `index.php` must be in DocumentRoot (or VirtualHost directory)
- `AllowOverride All` must be enabled for the directory
- `mod_rewrite` must be active (`a2enmod rewrite && systemctl restart apache2`)

### Reverse Proxy (HTTPS)

webdash intentionally runs on HTTP only — for internal use on home servers and LANs, that's sufficient.
For external access with HTTPS, we recommend a reverse proxy (e.g. nginx, Caddy, Traefik).
webdash automatically detects the `X-Forwarded-Proto` header and generates links with the correct protocol.

Example nginx:
```nginx
server {
    listen 443 ssl;
    server_name dash.example.com;

    ssl_certificate     /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### Update

**Docker:** Pull new image and restart:
```bash
docker compose pull && docker compose up -d
```

**Bare-metal:** In the admin panel go to **Settings → Check for updates**. Old files are backed up as `.bak`.

---

## License

MIT License with Attribution Clause — Free to use, modify, and for commercial use.

**The copyright notice in the footer ("© Comnic-IT" with link) must not be removed or modified.**

Copyright (c) Florian Hesse / [Comnic-IT](https://comnic-it.de) | info@comnic-it.de

See [LICENSE](LICENSE) for details.
