FROM php:7.4-cli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && apt-get install -y git

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

COPY . /app
WORKDIR /app

RUN /usr/local/bin/composer install

CMD [ "php", "-S", "0.0.0.0:8000", "-t", "public" ]