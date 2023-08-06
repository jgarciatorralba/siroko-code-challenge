<?php

declare(strict_types=1);

namespace App\Products\Domain\Service;

use App\Products\Domain\Contract\ProductRepository;
use App\Products\Domain\Exception\ProductInUseException;
use App\Products\Domain\Product;

final class DeleteProduct
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function __invoke(Product $product): void
    {
        if (!$product->cartItems()->isEmpty()) {
            throw new ProductInUseException($product->id());
        }

        $this->productRepository->delete($product);
    }
}
