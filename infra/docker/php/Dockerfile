FROM php:7.3-apache-buster

# https://qiita.com/YAJIMA/items/68de1bdeb71a921a718d
RUN a2enmod rewrite && mkdir -p /tmp/cache && docker-php-ext-install pdo_mysql
ENV TZ=UTC \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer \
  # Laravel environment
#   APP_SERVICES_CACHE=/tmp/cache/services.php \
#   APP_PACKAGES_CACHE=/tmp/cache/packages.php \
#   APP_CONFIG_CACHE=/tmp/cache/config.php \
#   APP_ROUTES_CACHE=/tmp/cache/routes.php \
#   APP_EVENTS_CACHE=/tmp/cache/events.php \
#   VIEW_COMPILED_PATH=/tmp/cache/views \
  SESSION_DRIVER=cookie \
  LOG_CHANNEL=stderr \
  DB_CONNECTION=mysql \
  DB_HOST=db \
  DB_PORT=3306 \
  DB_DATABASE=laravel_local \
  DB_USERNAME=phper \
  DB_PASSWORD=secret

COPY ./php.ini /usr/local/etc/php/php.ini
