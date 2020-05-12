FROM php:7.4-apache-buster

RUN apt-get update

# Update and install Git and VIM + necessary packages
RUN apt-get install -y --no-install-recommends \
    git zip unzip vim ssh zlib1g-dev libicu-dev libzip-dev cron && \
    apt-get clean && \
    docker-php-ext-install pdo_mysql pcntl zip bcmath intl && \
    docker-php-ext-enable opcache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Enable the Laravel scheduler
RUN echo "* * * * * www-data cd /var/www && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel

# Fix terminal size bug in interactive shell in container
RUN echo "reset -w" >> /root/.bashrc

COPY ./php.ini /usr/local/etc/php/conf.d/laravel.ini
COPY ./apache.conf /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite

WORKDIR /var/www

CMD env > /etc/environment && cron && apache2ctl -D FOREGROUND
