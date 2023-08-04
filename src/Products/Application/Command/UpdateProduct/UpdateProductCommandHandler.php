<?php

declare(strict_types=1);

namespace App\Products\Application\Command\UpdateProduct;

use App\Products\Domain\Service\GetProductById;
use App\Products\Domain\Service\UpdateProduct;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class UpdateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly GetProductById $getProductById,
        private readonly UpdateProduct $updateProduct
    ) {
    }

    public function __invoke(UpdateProductCommand $command): void
    {
        $product = $this->getProductById->__invoke(
            Uuid::fromString($command->id())
        );

        $this->updateProduct->__invoke($product, [
            'name' => $command->name(),
            'price' => $command->price(),
            'updatedAt' => $command->updatedAt()
        ]);
    }
}
