<?php

declare(strict_types=1);

namespace KK\PaymentMethodsReport;

use Automattic\WooCommerce\Admin\Loader;
use KK\PaymentMethodsReport\Exception\AssetException;

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

        add_action('admin_enqueue_scripts', [$this, 'enqueueIndex']);
    }

    /**
     * Enqueue the main JS file for this report.
     *
     * @return void
     */
    public function enqueueIndex(): void
    {
        if (!class_exists( 'Automattic\WooCommerce\Admin\Loader') || !Loader::is_admin_or_embed_page()) {
            return;
        }

        $info = $this->getAssetInfo('index');
        $url = $this->baseUri . 'dist/index.js';

        wp_register_script(
            'payment-methods-report',
            $url,
            $info['dependencies'],
            $info['version'],
            true
        );
        wp_enqueue_script('payment-methods-report');
    }

    /**
     * Get asset info for the given asset.
     *
     * @param string $name
     * @return array{dependencies: string[], version: string}
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

        return array_merge($defaults, $options);
    }
}
