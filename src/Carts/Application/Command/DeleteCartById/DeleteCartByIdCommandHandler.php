<?php

declare(strict_types=1);

namespace App\Carts\Application\Command\DeleteCartById;

use App\Carts\Domain\Service\DeleteCart;
use App\Carts\Domain\Service\GetCartById;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class DeleteCartByIdCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly GetCartById $getCartById,
        private readonly DeleteCart $deleteCart
    ) {
    }

    public function __invoke(DeleteCartByIdCommand $command): void
    {
        $cartId = Uuid::fromString($command->id());
        $cart = $this->getCartById->__invoke($cartId);

        $this->deleteCart->__invoke($cart);
    }
}
