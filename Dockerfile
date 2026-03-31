# Sử dụng image PHP + Apache chính thức từ Docker Hub
FROM php:8.1-apache

# Chép toàn bộ mã nguồn vào thư mục webroot của Apache trong container
COPY . /var/www/html/

RUN apt-get update \
    && apt-get install -y libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli

# Mở port 80
EXPOSE 80
