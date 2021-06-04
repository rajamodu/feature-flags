FROM php:8.0-fpm

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

# Install xsltproc for pslm html reports https://github.com/Roave/psalm-html-output
RUN apt-get update && apt-get install -y xsltproc

RUN apt-get update && apt-get install -y git

RUN docker-php-ext-install pdo_mysql

# php intl extention
RUN apt-get -y update && apt-get install -y libicu-dev && docker-php-ext-configure intl && docker-php-ext-install intl

# php zip extention
RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# nodejs & npm
RUN apt-get update && apt-get install -y nodejs npm

COPY ./php.ini /usr/local/etc/php/

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate --no-interaction;  php-fpm

EXPOSE 9000
