# Imagen base de PHP con Apache
FROM php:8.2-apache

# Copia los archivos de tu proyecto al directorio del servidor
COPY . /var/www/html/

# Habilita módulos comunes de PHP (si los necesitas)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expone el puerto que Render usa
EXPOSE 10000

# Cambia el puerto por defecto de Apache al que Render requiere (10000)
RUN sed -i 's/80/10000/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Inicia Apache al arrancar el contenedor
CMD ["apache2-foreground"]
