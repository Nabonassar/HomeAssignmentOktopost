FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y libzip-dev
RUN docker-php-ext-install zip ctype iconv session mysqli pdo pdo_mysql
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 80