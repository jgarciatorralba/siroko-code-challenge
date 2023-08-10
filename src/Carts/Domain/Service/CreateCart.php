<?php

declare(strict_types=1);

namespace App\Carts\Domain\Service;

use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Cart;

final class CreateCart
{
    public function __construct(
        private readonly CartRepository $cartRepository
    ) {
    }

    public function __invoke(Cart $cart): void
    {
        $this->cartRepository->create($cart);
    }
}
