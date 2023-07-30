<?php

declare(strict_types=1);

namespace App\Products\Application\Command\CreateProduct;

use App\Products\Domain\Product;
use App\Products\Domain\Service\CreateProduct;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class CreateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CreateProduct $createProduct
    ) {
    }

    public function __invoke(CreateProductCommand $command): void
    {
        $product = Product::create(
            id: Uuid::fromString($command->id()),
            name: $command->name(),
            price: $command->price()
        );

        $this->createProduct->__invoke($product);
    }
}
