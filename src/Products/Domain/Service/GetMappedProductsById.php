<?php

declare(strict_types=1);

namespace App\Products\Domain\Service;

use App\Shared\Domain\ValueObject\Uuid;
use App\Products\Domain\Contract\ProductRepository;
use App\Products\Domain\Exception\ProductNotFoundException;
use App\Products\Domain\Product;

final class GetMappedProductsById
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    /**
     * @param Uuid[] $ids
     * @return array<string, Product>
     */
    public function __invoke(array $ids): array
    {
        $products = $this->productRepository->findByCriteria(['id' => $ids]);
        $missingProducts = array_diff(
            array_map(fn (Uuid $id) => $id->value(), $ids),
            array_map(fn (Product $product) => $product->id()->value(), $products)
        );

        if (!empty($missingProducts)) {
            throw new ProductNotFoundException(
                Uuid::fromString(reset($missingProducts))
            );
        }

        $mappedProducts = [];
        foreach ($products as $product) {
            $mappedProducts[$product->id()->value()] = $product;
        }

        return $mappedProducts;
    }
}
