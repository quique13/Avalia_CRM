FROM php:7.3-apache

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/img

# https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions gd mysqli pdo_sqlsrv pdo_odbc wddx intl calendar exif pcntl bcmath zip

# Set the timezone in PHP
RUN echo "date.timezone = America/Guatemala" >> /usr/local/etc/php/conf.d/timezone.ini

# Set the system timezone
RUN ln -snf /usr/share/zoneinfo/America/Guatemala /etc/localtime && echo "America/Guatemala" > /etc/timezone

# extension=ext/php_intl.dll

COPY ./uploads.ini /usr/local/etc/php/conf.d/uploads.ini

ENV TZ=America/Guatemala