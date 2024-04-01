FROM mcr.microsoft.com/devcontainers/php:1-8.1-bookworm

ENV TZ=UTC

COPY ./.devcontainer/php.ini /usr/local/etc/php/conf.d/99-php.ini

RUN ln -snf "/usr/share/zoneinfo/$TZ" /etc/localtime && echo "$TZ" > /etc/timezone
