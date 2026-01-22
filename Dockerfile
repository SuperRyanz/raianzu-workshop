# PHP + Apache container for backend hosting
FROM php:8.2-apache

# Install mysqli extension and enable Apache modules
RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

# Copy application code
COPY . /var/www/html

# Ensure upload directories exist and are writable
RUN mkdir -p /var/www/html/assets/img/services /var/www/html/assets/img/parts \
    && chown -R www-data:www-data /var/www/html/assets/img/services /var/www/html/assets/img/parts \
    && chmod -R 775 /var/www/html/assets/img/services /var/www/html/assets/img/parts

# Optional: tune PHP upload limits (kept above 1MB app-level limit)
RUN { \
  echo "file_uploads = On"; \
  echo "upload_max_filesize = 8M"; \
  echo "post_max_size = 16M"; \
} > /usr/local/etc/php/conf.d/uploads.ini

# Expose default Apache port
EXPOSE 80

# Environment variables for DB (provided by platform at runtime)
# ENV DB_HOST=localhost
# ENV DB_USER=root
# ENV DB_PASS=secret
# ENV DB_NAME=raianzu_workshop

# Apache runs as www-data by default