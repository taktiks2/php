FROM php:8.1-fpm
WORKDIR /var/www

# システムパッケージのインストール
RUN apt-get update \
  && apt-get install -y \
  gcc \
  make \
  git \
  unzip \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  libpq-dev \
  curl \
  gnupg \
  openssl \
  && docker-php-ext-install pdo_mysql mysqli \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# Composerのインストール（公式イメージから）
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.jsのインストール
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get update \
  && apt-get install -y nodejs \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# php.iniのコピー
COPY php.ini /usr/local/etc/php/

# アプリケーションコードのコピー
COPY . /var/www

# 権限設定
RUN chown -R www-data:www-data /var/www
