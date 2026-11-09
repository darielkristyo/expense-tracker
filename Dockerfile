# Stage 1: install PHP dependencies
FROM composer:2.8 AS vendor
WORKDIR /app

# Copy dependency manifests first so Docker can reuse this layer.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader

# Copy application source only after dependencies are installed.
COPY . .
RUN composer dump-autoload --optimize --no-dev

# Stage 2: small runtime image
FROM php:8.4-apache AS runtime
WORKDIR /var/www/html

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && rm -f /etc/apache2/sites-enabled/000-default.conf

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2ensite 000-default.conf

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY --from=vendor /app /var/www/html
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint

RUN chmod +x /usr/local/bin/app-entrypoint \
    && chown -R www-data:www-data storage bootstrap/cache

ENV APP_ENV=local
ENV APP_DEBUG=true
EXPOSE 80

ENTRYPOINT ["app-entrypoint"]
CMD ["apache2-foreground"]
