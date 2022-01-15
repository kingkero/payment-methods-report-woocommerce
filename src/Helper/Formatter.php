<?php

declare(strict_types=1);

namespace KK\PaymentMethodsReport\Helper;

abstract class Formatter
{
    /**
     * Format a number to nice string without HTML.
     *
     * @param float $price
     * @return string
     */
    public static function priceNoHtml(float $price): string
    {
        $clean = strip_tags(wc_price($price));
        $clean = str_replace(
            [
                '&nbsp;',
                '&euro;',
                '&dollar;',
            ],
            [
                ' ',
                '€',
                '$',
            ],
            $clean
        );

        return $clean;
    }

    /**
     * Format a number nicely as percent value.
     *
     * Rounds to two decimals and adds a percent sign after the value.
     *
     * @param float $percent
     * @return string
     */
    public static function percent(float $percent): string
    {
        $percent = round($percent, 2) * 100;
        return $percent . ' %';
    }
}
