<?php
namespace App\Service;

class ProductService
{
    private static $products = [
        [
            'id' => 1,
            'nom' => 'Viande',
            'prix' => 10,
        ],
        [
            'id' => 2,
            'nom' => 'Parmesan',
            'prix' => 4,
        ],
        [
            'id' => 3,
            'nom' => 'Beurre demi-sel',
            'prix' => 2,
        ],
    ];

    public function getProducts(): array
    {
        return self::$products;
    }

    public function getProductById(int $productId): ?array
    {
        return self::$products[$productId - 1] ?? null;
    }
}
