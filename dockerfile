FROM wordpress:6.2.2-apache

RUN pecl install xdebug-3.3.1 && docker-php-ext-enable xdebug

COPY ./php.ini /usr/local/etc/php/
