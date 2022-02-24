FROM php:7.4-cli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt-get update && apt-get install -y git
#RUN apt install symfony-cli

COPY . /app
WORKDIR /app

RUN /usr/local/bin/composer install

CMD [ "php", "-S", "0.0.0.0:8000", "-t", "public" ]