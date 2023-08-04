<?php

declare(strict_types=1);

namespace App\Products\Domain\Service;

use App\Products\Domain\Contract\ProductRepository;
use App\Products\Domain\Product;

final class UpdateProduct
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    /**
     * @param array <string, string|float|null> $updatedData
     */
    public function __invoke(Product $product, array $updatedData): void
    {
        $hasChanged = false;

        if (!empty($updatedData['name']) && $updatedData['name'] !== $product->name()) {
            $product->updateName($updatedData['name']);
            $hasChanged = true;
        }
        if (!empty($updatedData['price']) && $updatedData['price'] !== $product->price()) {
            $product->updatePrice($updatedData['price']);
            $hasChanged = true;
        }

        if ($hasChanged) {
            $this->productRepository->update($product);
        }
    }
}
