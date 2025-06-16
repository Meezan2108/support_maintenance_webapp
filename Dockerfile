FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    unzip

RUN apt-get -y install unixodbc-dev
RUN pecl install sqlsrv-5.7.0preview
RUN pecl install pdo_sqlsrv-5.7.0preview

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install mbstring exif pcntl bcmath
RUN docker-php-ext-install zip
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

RUN chown -R www-data:www-data /var/www

# copy ssh private dan config ./docker-compose/gitlab-ssh /home/$user/.ssh
# Set working directory
WORKDIR /var/www

USER $user
