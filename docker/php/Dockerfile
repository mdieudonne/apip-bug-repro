FROM php:8.2-fpm

# PHP official image
# https://hub.docker.com/_/php

# Easy installation of PHP extensions in official PHP Docker images
# https://github.com/mlocati/docker-php-extension-installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions apcu \
    ctype \
    intl \
    opcache \
    ldap \
    mbstring \
    pgsql \
    pdo_pgsql \
    xdebug \
    xsl \
    zip \
    @composer

RUN apt update && \
    apt upgrade -y && \
    apt install -y git

## Install php-cs-fixer
RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o php-cs-fixer\
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

COPY ./conf.d/symfony.dev.ini /usr/local/etc/php/conf.d/symfony.ini

WORKDIR /var/www/symfony

