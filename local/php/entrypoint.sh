#!/usr/bin/env bash
set -Eeuo pipefail

if [ ! -f /var/www/composer.json ]; then
    # Base WordPress
    composer create-project roots/bedrock bedrock \
        && cp -R bedrock/* . \
        && rm -rf bedrock

    # Tools and plugins
    composer require wp-cli/wp-cli \
        && wp core install --url=http://localhost --title=Demo --admin_user=admin --admin_password=admin --admin_email=admin@example.com --skip-email \
        && wp plugin install woocommerce --activate \
        && wp plugin install wordpress-importer --activate

    # WooCommerce Wizard https://github.com/woocommerce/woocommerce/issues/18302
    wp option set woocommerce_store_address "123 Main Street" \
        && wp option set woocommerce_store_address_2 "" \
        && wp option set woocommerce_store_city "Toronto" \
        && wp option set woocommerce_default_country "CA:ON" \
        && wp option set woocommerce_store_postalcode "A1B2C3" \
        && wp option set woocommerce_currency "CAD" \
        && wp option set woocommerce_product_type "physical" \
        && wp option set woocommerce_allow_tracking "no" \
        && wp option set --format=json woocommerce_stripe_settings '{"enabled":"no","create_account":false,"email":false}' \
        && wp option set --format=json woocommerce_ppec_paypal_settings '{"reroute_requests":false,"email":false}' \
        && wp option set --format=json woocommerce_cheque_settings '{"enabled":"no"}' \
        && wp option set --format=json woocommerce_bacs_settings '{"enabled":"no"}' \
        && wp option set --format=json woocommerce_cod_settings '{"enabled":"yes"}' \
        && wp wc --user=admin tool run install_pages \
        && wp wc --user=admin payment_gateway update bacs --enabled=true \
        && wp option update woocommerce_show_marketplace_suggestions 'no' \
        && wp option update woocommerce_task_list_complete 'yes' \
        && wp option update woocommerce_task_list_welcome_modal_dismissed 'yes'

    # WooCommerce Demo Data
    wp import web/app/plugins/woocommerce/sample-data/sample_products.xml --authors=create

    # Custom Woo Demo Data
    CUSTOMER=$(wp wc customer create --email='customer@example.com' --user=1 --porcelain)

fi

exec "$@"
