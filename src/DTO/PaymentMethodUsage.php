<?php

declare(strict_types=1);

namespace KK\PaymentMethodsReport\DTO;

use JsonSerializable;

class PaymentMethodUsage implements JsonSerializable
{
    protected string $name;
    protected int $absoluteUsage;
    protected int $relativeUsage;
    protected float $totalAmount;

    public function __construct(
        string $name,
        int $absoluteUsage,
        int $relativeUsage,
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
                'display' => '0.' . $this->relativeUsage . ' %',
                'value' => $this->relativeUsage,
            ],
            [
                'display' => $this->totalAmount . ' €',
                'value' => $this->totalAmount,
            ],
        ];
    }
}
