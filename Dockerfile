FROM richarvey/nginx-php-fpm:latest

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN apk add --no-cache ca-certificates \
    && composer install --no-dev --optimize-autoloader --no-interaction \
    && chmod +x /var/www/html/scripts/*.sh \
    && chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV LOG_CHANNEL stderr

CMD ["/start.sh"]