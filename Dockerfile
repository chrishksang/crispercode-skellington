FROM dunglas/frankenphp:1-php8.4-alpine AS base

RUN install-php-extensions intl pdo_mysql pdo_sqlite pdo_pgsql
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

ADD Caddyfile /etc/frankenphp/Caddyfile

RUN mkdir /sessions

FROM base AS development

RUN install-php-extensions xdebug

ADD docker/php/php-local.ini /usr/local/etc/php/conf.d/custom.ini

FROM base AS production

ADD docker/php/php-production.ini /usr/local/etc/php/conf.d/custom.ini

RUN mkdir -p /srv
COPY src /srv/src
COPY composer.json composer.lock /srv/
WORKDIR /srv
RUN composer install --no-dev --optimize-autoloader
