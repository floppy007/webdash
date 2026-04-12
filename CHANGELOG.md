# Changelog

Dieses Projekt dokumentiert Releases zweisprachig in Deutsch und Englisch.

---

## v1.78

### Änderungen

- **Session-Handling gehärtet**: Nach erfolgreichem Login wird die Session-ID jetzt regeneriert und beim Logout sauber zerstört
- **Schreibende Admin-Aktionen auf POST umgestellt**: Löschen, Logout, Logo-Entfernung und Update-Auslösung laufen nicht mehr über verändernde `GET`-Requests
- **Interne Admin-Aktionen konsistenter gemacht**: UI und JavaScript wurden an die POST-basierten Abläufe angepasst

### Changes

- **Hardened session handling**: The session ID is now regenerated after successful login and destroyed cleanly on logout
- **Switched mutating admin actions to POST**: Delete actions, logout, logo removal and update triggers no longer use mutating `GET` requests
- **Made internal admin actions more consistent**: UI and JavaScript were updated to match the new POST-based flows

---

## v1.77

### Änderungen

- **Proxmox-Health-Check korrigiert**: Einige Oberflächen wie Proxmox antworten auf `HEAD` mit `501 method not available`, obwohl normales `GET` sauber funktioniert
- **HTTP-Fallback für Reachability-Checks**: webdash versucht jetzt zuerst `HEAD` und fällt bei `405` oder `501` automatisch auf `GET` zurück
- **Fehlalarme bei Web-UIs reduziert**: Dienste mit eingeschränkter `HEAD`-Unterstützung werden dadurch nicht mehr fälschlich als Fehler markiert

### Changes

- **Fixed Proxmox health checks**: Some interfaces such as Proxmox return `501 method not available` for `HEAD` even though regular `GET` works correctly
- **HTTP fallback for reachability checks**: webdash now tries `HEAD` first and automatically falls back to `GET` on `405` or `501`
- **Reduced false error states for web UIs**: Services with limited `HEAD` support are no longer marked as failed incorrectly

---

## v1.76

### Änderungen

- **Docker-URL-Auflösung robuster gemacht**: Container-Links nutzen jetzt zuerst explizite Angaben wie `webdash.url`, `webdash.host`, `webdash.scheme`, `webdash.path` und veröffentlichte Host-Ports statt blind interne Container-Ports zu raten
- **HTTP-Status für Docker-Container konfigurierbar**: Mit `WEBDASH_DOCKER_HEALTH_MODE` kann zwischen reinem Docker-State, echtem HTTP-Check und deaktivierter Reachability-Prüfung gewählt werden
- **Traefik-Labels besser unterstützt**: Host-Regeln aus Traefik-Routern werden zur URL-Ermittlung berücksichtigt
- **Doku für Docker-Netzwerkfälle ergänzt**: Reverse-Proxy-, Domain- und Container-Netzwerk-Szenarien sind jetzt dokumentiert

### Changes

- **More robust Docker URL resolution**: Container links now prefer explicit values like `webdash.url`, `webdash.host`, `webdash.scheme`, `webdash.path` and published host ports instead of blindly guessing internal container ports
- **Configurable HTTP status handling for Docker containers**: `WEBDASH_DOCKER_HEALTH_MODE` allows choosing between Docker state only, real HTTP checks, or disabled reachability checks
- **Improved Traefik label support**: Host rules from Traefik routers are now considered when building target URLs
- **Added Docker networking documentation**: Reverse proxy, domain-based and container-network scenarios are now documented
