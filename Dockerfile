# Použijte oficiální PHP obraz s rozšířením FPM pro PHP 8.2
FROM php:8.2-fpm

# Nastavení pracovního adresáře
WORKDIR /var/www

# Nainstalujte systémové závislosti
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev

# Vyčistěte cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalace PHP rozšíření
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalace Composeru
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Zkopírujte aplikaci do kontejneru
COPY . /var/www

# Nastavení oprávnění pro Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expozice portu 9000 a spuštění PHP-FPM serveru
EXPOSE 9000
CMD ["php-fpm"]
