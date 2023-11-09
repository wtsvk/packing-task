<?php

declare(strict_types=1);

namespace App\Product;

class ProductCollection
{
    /**
     * @var Product[]
     */
    private array $products;

    /**
     * @param Product[] $products
     */
    public function __construct($products)
    {
        $this->products = $this->sortProducts($products);
    }

    public function getCollectionPackagingIdentifier(): int
    {
        $productIdentifiers = array_map(
            static fn (Product $product) => $product->getPackagingIdentifier(),
            $this->products
        );

        return crc32(implode(',', $productIdentifiers));
    }

    /**
     * @param Product[] $products
     * @return Product[]
     */
    private function sortProducts(array $products): array
    {
        usort($products, static function (Product $p1, Product $p2): int {
            return $p1->getPackagingIdentifier() <=> $p2->getPackagingIdentifier();
        });

        return array_values($products);
    }
}
