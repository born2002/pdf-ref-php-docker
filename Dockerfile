FROM php:7.4-apache

# Copy .htaccess file if needed
# COPY .htaccess /var/www/html/.htaccess

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules and set X-Frame-Options header
RUN a2enmod rewrite headers \
    && a2enmod ssl \
    # Uncomment the following line if you need socache_shmcb for SSL caching
    # && a2enmod socache_shmcb \
    && echo "Header always set X-Frame-Options 'SAMEORIGIN'" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride All None/AllowOverride All /' /etc/apache2/apache2.conf \
    # && chmod 644 /var/www/html/.htaccess \
    && service apache2 restart

# Uncomment and modify the following lines if you have custom SSL certificates
# RUN sed -i '/SSLCertificateFile.*snakeoil\.pem/c\SSLCertificateFile \/etc\/ssl\/certs\/mycert.crt' /etc/apache2/sites-available/default-ssl.conf \
#     && sed -i '/SSLCertificateKeyFile.*snakeoil\.key/c\SSLCertificateKeyFile \/etc\/ssl\/private\/mycert.key' /etc/apache2/sites-available/default-ssl.conf \
#     && a2ensite default-ssl \
#     && service apache2 restart

# Copy project files and set permissions
COPY ./ /var/www/html/
RUN chown -R www-data:www-data /var/www/html
# RUN chmod -R 700 /var/www/html/tmp  # Uncomment if specific permissions are required for the tmp folder

# Expose the HTTP port
EXPOSE 80
