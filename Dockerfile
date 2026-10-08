FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
        libsqlite3-dev \
    && docker-php-ext-install curl pdo_sqlite \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Directorio público de Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copiar todo el proyecto
COPY . /var/www/html/

# Permisos de la base de datos
RUN chown www-data:www-data /var/www/html/tessa.db \
    && chmod 664 /var/www/html/tessa.db

# Configuración de Apache
RUN printf '%s\n' \
    'DirectoryIndex index.php index.html' \
    > /etc/apache2/conf-available/custom-directory-index.conf \
    && a2enconf custom-directory-index

# Rewrite de las rutas
RUN printf '%s\n' \
    'RewriteEngine On' \
    'RewriteCond %{REQUEST_FILENAME} !-f' \
    'RewriteCond %{REQUEST_FILENAME} !-d' \
    'RewriteRule ^ index.php [QSA,L]' \
    > /var/www/html/public/.htaccess

EXPOSE 80

CMD ["apache2-foreground"]