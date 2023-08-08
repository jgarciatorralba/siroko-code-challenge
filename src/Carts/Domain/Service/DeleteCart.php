<?php

declare(strict_types=1);

namespace App\Carts\Domain\Service;

use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Cart;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;

final class DeleteCart
{
    public function __construct(
        private readonly CartRepository $cartRepository
    ) {
    }

    public function __invoke(Cart $cart): void
    {
        if ($cart->isConfirmed()) {
            throw new CartAlreadyConfirmedException($cart->id());
        }

        $this->cartRepository->delete($cart);
    }
}
