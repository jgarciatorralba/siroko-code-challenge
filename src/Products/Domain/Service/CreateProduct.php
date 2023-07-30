<?php

declare(strict_types=1);

namespace App\Products\Domain\Service;

use App\Products\Domain\Contract\ProductRepository;
use App\Products\Domain\Product;

final class CreateProduct
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function __invoke(Product $product): void
    {
        $this->productRepository->create($product);
    }
}
