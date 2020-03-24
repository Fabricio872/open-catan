FROM php:apache-buster

WORKDIR /var/www/html

RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install opcache \
    && docker-php-ext-install zip

RUN apt-get install -y git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "memory_limit=2048M\nmax_file_uploads=1024\nupload_max_filesize=64M\npost_max_size=2048M\nmax_execution_time=120" > /usr/local/etc/php/php.ini
COPY init.sh /init.sh

EXPOSE 80
CMD ["/bin/bash", "/init.sh"]
