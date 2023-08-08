<?php

declare(strict_types=1);

namespace App\Carts\Application\Command\ConfirmCartById;

use App\Carts\Domain\Service\GetCartById;
use App\Carts\Domain\Service\ConfirmCart;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class ConfirmCartByIdCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly GetCartById $getCartById,
        private readonly ConfirmCart $confirmCart
    ) {
    }

    public function __invoke(ConfirmCartByIdCommand $command): void
    {
        $cartId = Uuid::fromString($command->id());
        $cart = $this->getCartById->__invoke($cartId);

        $this->confirmCart->__invoke($cart);
    }
}
