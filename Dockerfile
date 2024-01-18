
FROM php:8.2-cli

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install pcntl && docker-php-ext-install bcmath

COPY . /var/www/html

WORKDIR /var/www/html

RUN curl -s https://getcomposer.org/installer | php

RUN php composer.phar install --no-interaction

CMD ["php", "artisan", "app:calculator"]