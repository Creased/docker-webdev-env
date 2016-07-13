#
# PHP-FPM Dockerfile
#
# Written by:
#   Baptiste MOINE <bap.moine.86@gmail.com>
#

# Pull base image.
# FROM php:5.6-fpm
FROM php:7-fpm

MAINTAINER Baptiste MOINE <bap.moine.86@gmail.com>

# Install PHP-FPM extensions.
RUN apt-get update && apt-get install -y \
        locales-all \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        bzip2 \
        libzip2 \
        libbz2-dev \
        libicu-dev \
        icu-devtools \
        libsqlite3-dev \
        libssl-dev \
        libxslt1-dev \
        libtidy-dev \
        libcurl3-dev \
        libxml2-dev \
        libzzip-dev \
    && docker-php-ext-install bcmath bz2 calendar curl exif ftp hash iconv intl json mcrypt mbstring mysqli pdo_mysql opcache pdo_sqlite phar session shmop simplexml soap tidy tokenizer xml xmlrpc xsl zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

# Create Nginx file structure.
RUN rm -rf /usr/share/nginx/html/ \
&& mkdir -p /usr/share/nginx/static \
&& mkdir -p /usr/share/nginx/webroot

# Create volumes.
VOLUME ["/etc/php5/fpm/pool.d/", "/usr/share/nginx/", "/usr/local/etc/php/"]

# Set workdir.
WORKDIR /usr/share/nginx/webroot/

