FROM php:8.1-fpm-alpine AS base

COPY --from=composer:2.7.2 /usr/bin/composer /usr/local/bin/composer
COPY --from=wordpress:cli-2.10.0-php8.1 /usr/local/bin/wp /usr/local/bin/wp

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/download/2.2.5/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    gd \
    xdebug \
    bcmath \
    exif \
    intl \
    mysqli \
    zip \
    opcache

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN set -eux; \
	docker-php-ext-enable opcache; \
	{ \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# https://wordpress.org/support/article/editing-wp-config-php/#configure-error-logging
RUN { \
# https://www.php.net/manual/en/errorfunc.constants.php
# https://github.com/docker-library/wordpress/issues/420#issuecomment-517839670
		echo 'error_reporting = E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_RECOVERABLE_ERROR'; \
		echo 'display_errors = Off'; \
		echo 'display_startup_errors = Off'; \
		echo 'log_errors = On'; \
		echo 'error_log = /dev/stderr'; \
		echo 'log_errors_max_len = 1024'; \
		echo 'ignore_repeated_errors = On'; \
		echo 'ignore_repeated_source = Off'; \
		echo 'html_errors = Off'; \
	} > /usr/local/etc/php/conf.d/error-logging.ini

#########
# STAGE #
#########
FROM base AS wordpress

#COPY ./entrypoint.sh /usr/local/bin/entrypoint

RUN mkdir -p /var/www
WORKDIR /var/www

# TODO:  fix entrypoint

#ENTRYPOINT ["/usr/local/bin/entrypoint"]

#########
# STAGE #
#########
FROM wordpress AS plugin

RUN apk add --no-cache \
    bash \
    git \

#WORKDIR /var/www/web/app/plugins/payment-methods-report-woocommerce
