FROM php:7.0-cli

RUN apt-get update -y \
    && apt-get install -y unzip \
    && apt-get install -y curl \
    && apt-get install -y zlib1g-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/* /usr/share/man/* /usr/share/doc/* /var/cache/* /tmp/* \
    # Install Composer version 1
    && cd /tmp \
    && php -r "readfile('https://getcomposer.org/installer');" > /tmp/composer-installer \
    && php /tmp/composer-installer --1 \
    && mv composer.phar /usr/local/bin/composer \
    && rm /tmp/composer-installer

ENV PATH="${PATH}:/code/vendor/bin"

#COPY . /code
WORKDIR /code

ENTRYPOINT ["tests/docker/php/entrypoint.sh"]
CMD ["sleep", "infinity"]