#!/bin/bash
# App-Dateien bei jedem Start aus dem Image ins Volume aktualisieren
cp /opt/webdash/app.php /var/www/html/.dashboard/app.php

# Standard-Assets nur kopieren wenn sie fehlen
for f in app-logo-dark.png app-logo-light.png favicon-dark.png favicon-light.png; do
    [ -f "/var/www/html/.dashboard/$f" ] || cp "/opt/webdash/$f" "/var/www/html/.dashboard/$f"
done

# Docker-Socket-Berechtigung: www-data braucht Zugriff auf den Docker Socket
if [ -S /var/run/docker.sock ]; then
    DOCKER_GID=$(stat -c '%g' /var/run/docker.sock 2>/dev/null)
    if [ -n "$DOCKER_GID" ] && [ "$DOCKER_GID" != "0" ]; then
        groupadd -g "$DOCKER_GID" dockerhost 2>/dev/null || true
        usermod -aG dockerhost www-data
    else
        # Socket gehört root → www-data zu root-Gruppe oder chmod
        chmod 660 /var/run/docker.sock 2>/dev/null || true
        chgrp www-data /var/run/docker.sock 2>/dev/null || true
    fi
fi

chown -R www-data:www-data /var/www/html/.dashboard

exec apache2-foreground
