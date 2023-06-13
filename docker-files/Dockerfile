FROM webdevops/php:8.2
COPY . /var/www

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        git \
        php8.2-http \
        php8.2-mongodb \
        php8.2-oauth \
        php8.2-pgsql \
        php8.2-psr \
        php8.2-raphf \
        php8.2-redis \
        php8.2-soap \
        php8.2-swoole; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
