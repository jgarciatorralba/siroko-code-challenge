<?php

declare(strict_types=1);

namespace App\Products\Application\Command\DeleteProductById;

use App\Products\Domain\Service\DeleteProduct;
use App\Products\Domain\Service\GetProductById;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class DeleteProductByIdCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly GetProductById $getProductById,
        private readonly DeleteProduct $deleteProduct
    ) {
    }

    public function __invoke(DeleteProductByIdCommand $command): void
    {
        $productId = Uuid::fromString($command->id());
        $product = $this->getProductById->__invoke($productId);

        $this->deleteProduct->__invoke($product);
    }
}
