FROM php:7.2-fpm-alpine

RUN apk update && apk add curl && \
  curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache --virtual .persistent-deps \
    icu-libs \
    libxml2-dev 
RUN  docker-php-ext-configure soap --enable-soap \ 
    && docker-php-ext-install -j$(nproc) \
        soap

WORKDIR /var/www

COPY ./src/composer.json ./src/composer.lock ./
RUN composer install --no-scripts --no-autoloader

RUN chown -R www-data:www-data /var/www