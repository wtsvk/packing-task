<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity]
class ProductCollectionPackaging
{

    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    private int $productCollectionId;

    #[ORM\ManyToOne(targetEntity: Packaging::class)]
    #[ORM\JoinColumn(name: 'packaging_id', referencedColumnName: 'id', nullable: false)]
    private Packaging $packaging;

    public function __construct(int $productCollectionId, Packaging $packaging)
    {
        $this->productCollectionId = $productCollectionId;
        $this->packaging = $packaging;
    }

    public function getProductCollectionId(): int
    {
        return $this->productCollectionId;
    }

    public function getPackaging(): Packaging
    {
        return $this->packaging;
    }
}
