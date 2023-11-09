<?php

declare(strict_types=1);

namespace App\Packaging\Provider;

use App\Entity\Packaging;
use App\Product\ProductCollection;
use Doctrine\ORM\EntityManager;

class FallbackProvider implements ProviderInterface
{
    public function __construct(
        private EntityManager $entityManager,
    ) {}

    public function findPackaging(ProductCollection $products): ?Packaging
    {
        $packaging = $this
            ->entityManager
            ->getRepository(Packaging::class)
            ->findOneBy([], ['id' => 'DESC']);

        return $packaging;
    }
}