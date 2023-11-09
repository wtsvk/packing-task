<?php

declare(strict_types=1);

namespace App\Packaging;

use App\Entity\Packaging;
use App\Product\ProductCollection;
use App\Packaging\Provider\ProviderInterface;

class PackagingFinder
{

    /**
     * @param ProviderInterface[] $providers
     */
    public function __construct(
        private PackagingStorage $storage,
        private array $providers,
    ) {}

    public function findPackaging(ProductCollection $products): ?Packaging
    {
        foreach ($this->providers as $provider) {
            $packaging = $provider->findPackaging($products);

            if ($packaging !== null) {
                $this->storage->persist($products, $packaging);
                return $packaging;
            }
        }

        return null;
    }
}
