<?php

declare(strict_types=1);

namespace KK\PaymentMethodsReport\Rest;

use WP_REST_Request;
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

    public static function getItems(WP_REST_Request $request): array
    {
        return [
            [
                [
                    'display' => 'Paypal',
                    'value' => 'Paypal',
                ],
                [
                    'display' => '1234',
                    'value' => 1234,
                ],
                [
                    'display' => '35 %',
                    'value' => 35,
                ],
                [
                    'display' => '581,41 €',
                    'value' => 581.41,
                ],
            ],
            [
                [
                    'display' => 'Credit Card',
                    'value' => 'Credit Card',
                ],
                [
                    'display' => '4321',
                    'value' => 4321,
                ],
                [
                    'display' => '53 %',
                    'value' => 53,
                ],
                [
                    'display' => '185,41 €',
                    'value' => 185.41,
                ],
            ],
        ];
    }

    public static function getItemsPermissionsCheck(): bool
    {
        return true;
        // return current_user_can('view_woocommerce_reports');
    }
}
