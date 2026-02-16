FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y libcurl4-openssl-dev \
    && a2enmod rewrite \
    && docker-php-ext-install curl \
    && rm -rf /var/lib/apt/lists/*

RUN echo '<Directory /var/www/html>\n AllowOverride All\n</Directory>' \
    > /etc/apache2/conf-enabled/allowoverride.conf

COPY index.php /var/www/html/
COPY .htaccess /var/www/html/

# App-Dateien ins Backup-Verzeichnis (werden per Entrypoint ins Volume kopiert)
COPY .dashboard/app.php /opt/webdash/
COPY .dashboard/app-logo-dark.png /opt/webdash/
COPY .dashboard/app-logo-light.png /opt/webdash/
COPY .dashboard/favicon-dark.png /opt/webdash/
COPY .dashboard/favicon-light.png /opt/webdash/
COPY .dashboard/wallpapers/ /opt/webdash/wallpapers/

RUN mkdir -p /var/www/html/.dashboard \
    && chown -R www-data:www-data /var/www/html/.dashboard

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
VOLUME /var/www/html/.dashboard

EXPOSE 80
ENTRYPOINT ["docker-entrypoint.sh"]
