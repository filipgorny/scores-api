FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu
RUN docker-php-ext-enable apcu

RUN apt-get update

RUN apt-get install libcurl3-dev -y
RUN docker-php-ext-install curl

RUN apt-get install git -y

# install mongodb ext
RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && mv composer /usr/local/bin/composer

WORKDIR /usr/src/app

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin