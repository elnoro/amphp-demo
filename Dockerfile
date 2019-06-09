FROM composer as deps

WORKDIR /app/
COPY composer.json .
COPY composer.lock .

RUN composer install --no-ansi --no-dev --no-interaction --no-scripts --optimize-autoloader

FROM php:7.3-alpine3.9

WORKDIR /app/
COPY --from=deps /app/ .
COPY . .

ENTRYPOINT ["php", "index.php"]
