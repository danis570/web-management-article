# Gunakan image PHP resmi yang sudah include web server Apache
FROM php:8.2-apache

# Instal ekstensi MySQL (PDO dan mysqli) jika website Anda menggunakan database
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Ubah DocumentRoot Apache agar langsung mengarah ke folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Aktifkan modul mod_rewrite Apache (sangat penting untuk routing/clean URL di MVC)
RUN a2enmod rewrite

# Salin seluruh project Anda ke dalam container
COPY . /var/www/html/

# Pastikan folder uploads memiliki izin tulis jika aplikasi Anda ada fitur upload file
RUN chown -R www-data:www-data /var/www/html/public/uploads

# Buka port 80
EXPOSE 80