FROM php:7.4-cli

RUN apt-get update -y \
    && apt-get install -y unzip \
    && apt-get install -y curl \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/* /usr/share/man/* /usr/share/doc/* /var/cache/* /tmp/* \
    # Install Composer version 1
    && cd /tmp \
    && php -r "readfile('https://getcomposer.org/installer');" > /tmp/composer-installer \
    && php /tmp/composer-installer --1 \
    && mv composer.phar /usr/local/bin/composer \
    && rm /tmp/composer-installer

# Development config
RUN sed -i -e "s/^memory_limit\s*=.*/memory_limit = -1/" /usr/local/etc/php/php.ini-development
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

ENV PATH="${PATH}:/code/vendor/bin"

COPY ./tests/docker/php/entrypoint.sh /entrypoint.sh
RUN ["chmod", "+x", "/entrypoint.sh"]

WORKDIR /code

ENTRYPOINT ["/entrypoint.sh"]
CMD ["sleep", "infinity"]