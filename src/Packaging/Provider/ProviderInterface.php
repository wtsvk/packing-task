<?php

declare(strict_types=1);

namespace App\Packaging\Provider;

use App\Entity\Packaging;
use App\Product\ProductCollection;

interface ProviderInterface
{
    public function findPackaging(ProductCollection $products): ?Packaging;
}