FROM framenetbrasil/apache-php:v3.8.2

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
