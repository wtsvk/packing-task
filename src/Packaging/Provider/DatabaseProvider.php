<?php

declare(strict_types=1);

namespace App\Packaging\Provider;

use App\Entity\Packaging;
use App\Entity\ProductCollectionPackaging;
use App\Packaging\PackagingStorage;
use App\Product\ProductCollection;
use Doctrine\ORM\EntityManager;

class DatabaseProvider implements ProviderInterface
{
    public function __construct(
        private PackagingStorage $storage,
    ) {}

    public function findPackaging(ProductCollection $products): ?Packaging
    {
        return $this->storage->find($products);
    }
}