FROM mcr.microsoft.com/devcontainers/php:1-8.1-bookworm

ARG WWWGROUP
ARG WWWUSER

ENV TZ=UTC

RUN ln -snf "/usr/share/zoneinfo/$TZ" /etc/localtime && echo "$TZ" > /etc/timezone

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y ca-certificates procps dnsutils gnupg curl build-essential unzip zip \
    && mkdir -p /var/log/xdebug

COPY ./php.ini /usr/local/etc/php/conf.d/99-php.ini
