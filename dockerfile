# Imagen base con Apache y PHP
FROM php:8.2-apache

# Instalar extensiones necesarias (ej. mysqli para MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar tu código al contenedor
COPY ./app /var/www/html/

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80

