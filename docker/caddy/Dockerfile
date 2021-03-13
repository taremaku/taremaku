FROM thecodingmachine/php:8.0-v4-fpm AS php

FROM caddy:2.3.0-builder-alpine AS caddy_builder

RUN xcaddy build \
    --with github.com/dunglas/mercure/caddy \
    --with github.com/dunglas/vulcain/caddy \
    --with github.com/gamalan/caddy-tlsredis

FROM caddy:2.3.0 AS caddy

WORKDIR /var/www/html

COPY --from=dunglas/mercure:v0.11 /srv/public /var/www/html/mercure-assets/
COPY --from=caddy_builder /usr/bin/caddy /usr/bin/caddy
COPY Caddyfile /etc/caddy/Caddyfile
