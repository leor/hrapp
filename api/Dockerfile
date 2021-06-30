FROM php:7-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    zip \
    vim \
    unzip \
    git \
    curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/api
ADD . /var/www/api/

EXPOSE 9000
