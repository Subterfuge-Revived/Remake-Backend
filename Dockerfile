# Dockerfile
FROM php:7.4-cli

RUN apt-get update -y

# Update and install Git and VIM + necessary packages
RUN apt-get install -y --no-install-recommends \
    git zip unzip vim ssh zlib1g-dev libicu-dev libzip-dev cron libmcrypt-dev && \
    apt-get clean && \
    docker-php-ext-install pdo pdo_mysql pcntl zip bcmath intl && \
    docker-php-ext-enable opcache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable the Laravel scheduler
RUN echo "* * * * * www-data cd /var/www && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel

# Fix terminal size bug in interactive shell in container
RUN echo "reset -w" >> /root/.bashrc

WORKDIR /var/www
COPY . /var/www

EXPOSE 80
ENTRYPOINT ["tail", "-f", "/dev/null"]
