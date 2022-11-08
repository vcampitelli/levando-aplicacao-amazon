FROM php:8.1-cli-alpine

WORKDIR /app

# Instalando pacotes
RUN docker-php-ext-install pdo pdo_mysql

# Instalando depdendências
COPY ["composer.json", "composer.lock", "./"]
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --no-interaction

# Copiando resto da aplicação
COPY . ./

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
