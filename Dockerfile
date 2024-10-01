FROM php:8.1-cli

# Installer les dépendances nécessaires pour Composer
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

EXPOSE 8000
# commande pour démarer le projet sur localhost:8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
