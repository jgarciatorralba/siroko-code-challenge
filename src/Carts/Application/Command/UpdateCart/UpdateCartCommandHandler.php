<?php

declare(strict_types=1);

namespace App\Carts\Application\Command\UpdateCart;

use App\Carts\Domain\Service\GetCartById;
use App\Carts\Domain\Service\UpdateCart;
use App\Carts\Domain\ValueObject\CartItemOperation;
use App\Products\Domain\Service\GetMappedProductsById;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class UpdateCartCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly GetMappedProductsById $getMappedProductsById,
        private readonly GetCartById $getCartById,
        private readonly UpdateCart $updateCart
    ) {
    }

    public function __invoke(UpdateCartCommand $command): void
    {
        $cart = $this->getCartById->__invoke(Uuid::fromString($command->id()));

        $productIds = [];
        /** @var array<string, string|int> $commandCartItemOperation */
        foreach ($command->operations() as $commandCartItemOperation) {
            $productIds[] = Uuid::fromString($commandCartItemOperation['productId']);
        }

        $mappedProducts = $this->getMappedProductsById->__invoke($productIds);
        $itemOperations = [];

        /** @var array<string, string|int> $commandCartItemOperation */
        foreach ($command->operations() as $commandCartItemOperation) {
            $itemOperations[] = CartItemOperation::create(
                type: $commandCartItemOperation['operation'],
                product: $mappedProducts[$commandCartItemOperation['productId']],
                quantity: $commandCartItemOperation['quantity'] ?? null,
                dateTime: $command->updatedAt()
            );
        }

        $this->updateCart->__invoke($cart, [
            'updatedAt' => $command->updatedAt(),
            'itemOperations' => $itemOperations
        ]);
    }
}
