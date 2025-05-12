# Escolher a imagem base do PHP
FROM php:8.1-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip libmcrypt-dev

# Instalar extensões PHP necessárias para o Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-install pdo pdo_mysql

# Instalar Composer (gestor de dependências PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar os arquivos do repositório para o container
COPY . .

# Instalar dependências PHP (Laravel)
RUN composer install

# Expor a porta 9000 para o servidor PHP
EXPOSE 9000

# Comando para iniciar o PHP-FPM
CMD ["php-fpm"]