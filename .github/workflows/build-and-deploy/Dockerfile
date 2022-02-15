FROM php:8 AS builder
WORKDIR /home
RUN mkdir src
WORKDIR /home/src
COPY ./. .
# 5. Composer.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip
RUN composer install --no-interaction --prefer-dist --ignore-platform-reqs

FROM node:lts AS npminstall
WORKDIR /home
RUN mkdir src
WORKDIR /home/src/
COPY --from=builder /home/src/.  .
RUN npm i -g npm
RUN npm ci
RUN npm run production
RUN rm -rf node_modules

FROM php:8-apache
COPY --from=npminstall /home/src/. /var/www/html/.
RUN apt-get update && apt-get install -y \
    cron \
    curl \
    g++ \
    git \
    sudo \
    libzip-dev \
    unzip \
    zip \
 && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install \
    bcmath \
    pdo_mysql \
    zip
# Setup cron task
RUN echo "* * * * * cd /var/www/html && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1" | crontab -
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers
# 4. Start with base PHP config, then add extensions.
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
CMD ["/usr/local/bin/php", "artisan", "migrate", "--force"]
CMD ["/usr/local/bin/php", "artisan", "storage:link"]
