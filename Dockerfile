# PHP 8.2-fpm ベース
FROM php:8.2-fpm

# 必要な PHP 拡張とツールをインストール
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Composer をインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 作業ディレクトリ
WORKDIR /var/www

# アプリをコピー
COPY . .

# Composer 依存関係をインストール
RUN composer install --no-dev --optimize-autoloader

# ストレージとキャッシュの権限設定
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Render がリダイレクトするポート
EXPOSE 10000

# php-fpm を起動（本番用）
CMD ["php-fpm"]
