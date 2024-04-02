# Establecer la imagen base
FROM php:8.2-fpm

# Instalar las extensiones necesarias de PHP y las herramientas de Laravel
RUN docker-php-ext-install pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establecer el directorio de trabajo en /var/www
WORKDIR /var/www

# Copiar los archivos del proyecto existente
COPY . /var/www

# Instalar las dependencias del proyecto
RUN composer install --optimize-autoloader --no-dev

# Cambiar los permisos de los directorios necesarios
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copiar el archivo .env.example a .env y generar una clave de aplicación
RUN cp .env.example .env && php artisan key:generate

# Optimizar la configuración y las rutas de Laravel
RUN php artisan config:cache && php artisan route:cache

# Exponer el puerto 9000 y empezar
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
