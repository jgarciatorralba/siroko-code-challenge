<?php

declare(strict_types=1);

namespace App\Products\Domain\Service;

use App\Shared\Domain\ValueObject\Uuid;
use App\Products\Domain\Contract\ProductRepository;
use App\Products\Domain\Exception\ProductNotFoundException;
use App\Products\Domain\Product;

final class GetProductById
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function __invoke(Uuid $id): Product
    {
        $product = $this->productRepository->findOneById($id);
        if ($product === null) {
            throw new ProductNotFoundException($id);
        }

        return $product;
    }
}
