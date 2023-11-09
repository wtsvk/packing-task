<?php

declare(strict_types=1);

namespace App\Product;

class Product
{
    public function __construct(
        private float $width,
        private float $height,
        private float $length,
        private float $weight,
    ) {}

    public function getPackagingIdentifier(): int
    {
        $dimensions = [
            $this->width,
            $this->height,
            $this->length,
        ];
        sort($dimensions);

        $identifier = implode(',', $dimensions) . ',' . $this->weight;
        return crc32($identifier);
    }
}
