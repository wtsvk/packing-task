<?php

declare(strict_types=1);

namespace App\Packaging;

use App\Entity\Packaging;
use App\Entity\ProductCollectionPackaging;
use App\Product\ProductCollection;
use Doctrine\ORM\EntityManager;

class PackagingStorage
{
    public function __construct(
        private EntityManager $entityManager,
    ) {}

    public function find(ProductCollection $products): ?Packaging
    {
        $productCollectionPackaging = $this
            ->entityManager
            ->find(ProductCollectionPackaging::class, $products->getCollectionPackagingIdentifier());

        return $productCollectionPackaging?->getPackaging();
    }

    public function persist(ProductCollection $products, Packaging $packaging): void
    {
        $productCollectionPackaging = $this
            ->entityManager
            ->find(ProductCollectionPackaging::class, $products->getCollectionPackagingIdentifier());

        if ($productCollectionPackaging === null) {
            $productCollectionPackaging = new ProductCollectionPackaging(
                $products->getCollectionPackagingIdentifier(),
                $packaging,
            );

            $this->entityManager->persist($productCollectionPackaging);
            $this->entityManager->flush();
        }
    }
}