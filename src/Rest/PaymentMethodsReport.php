<?php

declare(strict_types=1);

namespace KK\PaymentMethodsReport\Rest;

use Automattic\WooCommerce\Admin\Overrides\Order;
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

    /** @var string[] */
    protected const IGNORE_STATUS = [
        'wc-pending',
        'wc-on-hold',
        'wc-cancelled',
        'wc-refunded',
        'wc-failed',
    ];

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
        // TODO: use $request to filter response
        // TODO: cache response

        $data = self::getNicePaymentMethodUsages();

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
        return true;
        // return current_user_can('view_woocommerce_reports');
        // TODO: Fix permission
    }

    /**
     * @return PaymentMethodUsage[]
     */
    protected static function getNicePaymentMethodUsages(): array
    {
        $data = self::getTotalPaymentMethodUsage();

        $totalOrders = 0;
        foreach ($data as $entry) {
            $totalOrders += $entry['usage'];
        }

        $result = [];

        foreach ($data as $entry) {
            $result[] = new PaymentMethodUsage(
                $entry['name'],
                $entry['usage'],
                ($entry['usage'] / $totalOrders),
                $entry['amount']
            );
        }

        return $result;
    }

    /**
     * @return array<array{name: string, usage: int, amount: float}>
     */
    protected static function getTotalPaymentMethodUsage(): array
    {
        $result = [];

        $status = array_filter(
            array_keys(wc_get_order_statuses()),
            static fn (string $status): bool => !in_array($status, self::IGNORE_STATUS, true)
        );

        // TODO: maybe use custom SQL query instead
        $orders = wc_get_orders([
            'limit' => -1,
            'type' => 'shop_order',
            'status' => $status,
            'return' => 'objects',
        ]);
        if (!is_array($orders)) {
            return $result;
        }

        /** @var Order $order */
        foreach ($orders as $order) {
            $method = $order->get_payment_method();

            if (!array_key_exists($method, $result)) {
                $result[$method] = [
                    'name' => $order->get_payment_method_title(),
                    'usage' => 0,
                    'amount' => 0,
                ];
            }

            $result[$method]['usage']++;
            $result[$method]['amount'] += $order->get_total();
        }

        return $result;
    }
}
