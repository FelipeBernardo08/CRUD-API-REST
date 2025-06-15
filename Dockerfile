# Usa uma imagem oficial com PHP 8 + Apache
FROM php:8.2-apache

# Instala extensões necessárias
RUN docker-php-ext-install mysqli

# Habilita o mod_rewrite (importante para projetos com .htaccess)
RUN a2enmod rewrite

# Configura o Apache para permitir .htaccess
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do projeto
COPY . .

# Dá permissão para o Apache
RUN chown -R www-data:www-data /var/www/html
