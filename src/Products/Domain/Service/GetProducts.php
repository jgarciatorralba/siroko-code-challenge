<?php

declare(strict_types=1);

namespace App\Products\Domain\Service;

use App\Products\Domain\Contract\ProductRepository;
use App\Products\Domain\Product;

final class GetProducts
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    /** @return Product[] */
    public function __invoke(): array
    {
        return $this->productRepository->findAll();
    }
}
