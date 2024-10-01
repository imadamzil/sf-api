FROM php:8.1-cli

# Installer les dépendances nécessaires pour Composer
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

EXPOSE 8000
# commande pour démarer le projet sur localhost:8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
