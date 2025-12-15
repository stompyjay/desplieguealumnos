FROM php:8.3-apache

RUN a2dismod mpm_event mpm_worker || true

RUN a2enmod rewrite

COPY src/ /var/www/html/
