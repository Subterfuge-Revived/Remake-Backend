FROM php:7.4-apache-buster

RUN apt-get update

RUN apt-get install -y --no-install-recommends git zip unzip zlib1g-dev libzip-dev

# Enable the mysqli extension for PHP
RUN docker-php-ext-install mysqli zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Fix terminal size bug in interactive shell in container
RUN echo "reset -w" >> /root/.bashrc

# Configure PHP and Apache
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./apache.conf /etc/apache2/sites-enabled/000-default.conf
RUN a2enmod rewrite

WORKDIR /var/www

CMD env > /etc/environment && apache2ctl -D FOREGROUND
