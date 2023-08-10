<?php

declare(strict_types=1);

namespace App\Carts\Application\Command\CreateCart;

use App\Carts\Domain\Cart;
use App\Carts\Domain\CartItem;
use App\Carts\Domain\Service\CreateCart;
use App\Products\Domain\Service\GetProductById;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class CreateCartCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly GetProductById $getProductById,
        private readonly CreateCart $createCart
    ) {
    }

    public function __invoke(CreateCartCommand $command): void
    {
        $cart = Cart::create(
            id: Uuid::fromString($command->id()),
            createdAt: $command->createdAt(),
            updatedAt: $command->updatedAt()
        );

        //TODO: get all products in one query

        /** @var array<string, string|int> $commandCartItem */
        foreach ($command->items() as $commandCartItem) {
            $productId = Uuid::fromString($commandCartItem['productId']);
            $product = $this->getProductById->__invoke($productId);

            $cartItem = CartItem::create(
                id: Uuid::fromString($commandCartItem['id']),
                cart: $cart,
                product: $product,
                quantity: $commandCartItem['quantity'],
                createdAt: $command->createdAt(),
                updatedAt: $command->updatedAt()
            );

            $cart->addItem($cartItem);
        }

        $this->createCart->__invoke($cart);
    }
}
