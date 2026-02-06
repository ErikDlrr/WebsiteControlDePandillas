FROM ghcr.io/kingpin/php-docker:8.2-apache-bookworm

# Copiamos tu app al DocumentRoot
COPY app/ /var/www/html/

WORKDIR /var/www/html

