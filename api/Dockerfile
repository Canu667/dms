FROM php:7.2.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get -y --no-install-recommends install git \
    && curl -s https://getcomposer.org/installer | php && rm -rf /var/lib/apt/lists/*

RUN /usr/local/bin/docker-php-ext-install mysqli pdo pdo_mysql

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80