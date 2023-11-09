<?php

declare(strict_types=1);

namespace App\Packaging\Provider;

use App\Entity\Packaging;
use App\Product\ProductCollection;

class ApiPackingProvider implements ProviderInterface
{
    /**
     * @todo Implement this method
     */
    public function findPackaging(ProductCollection $products): ?Packaging
    {
        return null;
    }
}