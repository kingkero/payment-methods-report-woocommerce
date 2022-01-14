<?php

declare(strict_types=1);

namespace KK\PaymentMethodsReport\Rest;

use KK\PaymentMethodsReport\DTO\PaymentMethodUsage;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class PaymentMethodsReport
{
    /** @var string */
    protected const NAMESPACE = 'kk/pmr/v1';

    /** @var string */
    protected const BASE = 'report';

    /**
     * Register the route used in this controller.
     *
     * @return void
     */
    public static function registerRoute(): void
    {
        register_rest_route(
            self::NAMESPACE,
            '/' . self::BASE,
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [__CLASS__, 'getItems'],
                'permission_callback' => [__CLASS__, 'getItemsPermissionsCheck'],
                'args' => [],
            ]
        );
    }

    /**
     * Return the items.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function getItems(WP_REST_Request $request): WP_REST_Response
    {

        $paypal = new PaymentMethodUsage('PayPal', 159, 25, 753.31);

        $data = [
            $paypal,
            $paypal,
        ];

        $response = new WP_REST_Response($data);

        return $response;
    }

    /**
     * Check if current requestor is permitted.
     *
     * @return boolean
     */
    public static function getItemsPermissionsCheck(): bool
    {
        return current_user_can('view_woocommerce_reports');
    }
}
