<?php

declare(strict_types=1);

namespace App\Carts\Domain\Service;

use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Cart;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\EmptyCartException;
use DateTimeImmutable;

final class ConfirmCart
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
        if ($cart->items()->isEmpty()) {
            throw new EmptyCartException($cart->id());
        }

        $cart->updateIsConfirmed(true);
        $cart->updateSubtotal($cart->calculateSubtotal());
        $cart->updateUpdatedAt(new DateTimeImmutable());

        $this->cartRepository->update($cart);
    }
}
