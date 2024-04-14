<?php

declare(strict_types=1);

namespace KK\PaymentMethodsReport;

use Automattic\WooCommerce\Admin\Loader;
use KK\PaymentMethodsReport\Exception\AssetException;
use KK\PaymentMethodsReport\Rest\PaymentMethodsReport;

class Plugin
{
    protected string $baseDir;
    protected string $baseUri;

    /**
     * Start the plugin.
     *
     * @param string $baseDir Base directory of the plugin on the file system.
     * @param string $baseUri Base directory of the plugin for the web.
     */
    public function __construct(string $baseDir, string $baseUri)
    {
        $this->baseDir = $baseDir;
        $this->baseUri = $baseUri;

        add_action('admin_enqueue_scripts', [$this, 'enqueueIndex'], 10);
        add_filter('woocommerce_analytics_report_menu_items', [$this, 'reportPages'], 10);

        add_action('rest_api_init', function() {
            PaymentMethodsReport::registerRoute();
        });
    }

    /**
     * Enqueue the main JS file for this report.
     *
     * @return void
     */
    public function enqueueIndex(): void
    {
        try {
            $info = $this->getAssetInfo('index');
            $url = $this->baseUri . 'dist/index.js';

            wp_register_script(
                'payment-methods-report',
                $url,
                $info['dependencies'],
                $info['version'],
                true
            );

            if (
                /**
                 * Should the index.js file be enqueued?
                 * 
                 * @since 1.0.0
                 * @param bool $shouldEnqueue
                 */
                apply_filters('kk/paymentMethodsReport/indexJsEnqueue', true) === true
            ) {
                wp_enqueue_script('payment-methods-report');
            }
        } catch (AssetException $e) {
            if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG === true) {
                error_log('Could not load asset "index": ' . $e->getMessage());
            }
        }
    }

    /**
     * Get asset info for the given asset.
     *
     * @param string $name
     * @return array{dependencies: string[], version: string}
     * @throws AssetException
     */
    protected function getAssetInfo(string $name): array
    {
        $infoPath = $this->baseDir . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . $name . '.asset.php';
        if (!file_exists($infoPath)) {
            throw new AssetException('Config not found at ' . $infoPath);
        }

        $defaults = [
            'dependencies' => [],
            'version' => null,
        ];
        $options = require($infoPath);

        /**
         * Possibility to filter the asset info for any loaded asset.
         *
         * This hook is using the dynamic parameter $name. To add a depedency to the index dependencies, you could add
         * it via `kk/paymentMethodsReport/assetInfo_index`.
         *
         * @since 1.0.0
         * @param array{dependencies: string[], version: string} The asset info of the script.
         */
        return apply_filters(
            'kk/paymentMethodsReport/assetInfo_' . $name,
            array_merge($defaults, $options)
        );
    }

    /**
     * Add the report to navigation.
     *
     * @param array<int, array<string, mixed>> $reportPages
     * @return array<int, array<string, mixed>>
     */
    public function reportPages(array $reportPages): array
    {
        $entry = [
            'id' => 'payment-methods-used',
            'title' => __('Payment Methods', 'payment-methods-report-woocommerce'),
            'parent' => 'woocommerce-analytics',
            'path' => '/analytics/payment-methods-used',
        ];

        /**
         * Position 9 should still be above settings.
         * If there are less than 9, will [just insert at the end](https://3v4l.org/5NdoM).
         */
        array_splice($reportPages, 9, 0, [$entry]);

        return $reportPages;
    }
}
