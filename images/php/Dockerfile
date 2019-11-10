FROM php:7-fpm
RUN apt-get update && apt-get install -y \
        curl \
        wget \
        git \
    && docker-php-ext-install -j$(nproc) pdo_mysql mysqli

WORKDIR /var/www
CMD ["php-fpm"]
