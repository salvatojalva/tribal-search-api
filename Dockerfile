FROM php:7.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache --virtual .persistent-deps \
    icu-libs \
    libxml2-dev 
RUN  docker-php-ext-configure soap --enable-soap \ 
    && docker-php-ext-install -j$(nproc) \
        soap

WORKDIR /var/www