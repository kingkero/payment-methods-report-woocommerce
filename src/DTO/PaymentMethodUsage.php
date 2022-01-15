<?php

declare(strict_types=1);

namespace KK\PaymentMethodsReport\DTO;

use JsonSerializable;
use KK\PaymentMethodsReport\Helper\Formatter;

class PaymentMethodUsage implements JsonSerializable
{
    protected string $name;
    protected int $absoluteUsage;
    protected float $relativeUsage;
    protected float $totalAmount;

    public function __construct(
        string $name,
        int $absoluteUsage,
        float $relativeUsage,
        float $totalAmount
    ) {
        $this->name = $name;
        $this->absoluteUsage = $absoluteUsage;
        $this->relativeUsage = $relativeUsage;
        $this->totalAmount = $totalAmount;
    }

    public function jsonSerialize(): array
    {
        return [
            [
                'display' => $this->name,
                'value' => $this->name,
            ],
            [
                'display' => $this->absoluteUsage,
                'value' => $this->absoluteUsage,
            ],
            [
                'display' => Formatter::percent($this->relativeUsage),
                'value' => $this->relativeUsage,
            ],
            [
                'display' => Formatter::priceNoHtml($this->totalAmount),
                'value' => $this->totalAmount,
            ],
        ];
    }
}
