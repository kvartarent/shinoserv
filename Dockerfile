FROM php:8.1-apache

# Расширения PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Включаем mod_rewrite и mod_headers для .htaccess
RUN a2enmod rewrite headers

# Разрешаем .htaccess в DocumentRoot
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Настройка DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html
