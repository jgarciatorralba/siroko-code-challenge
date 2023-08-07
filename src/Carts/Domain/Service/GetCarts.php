<?php

declare(strict_types=1);

namespace App\Carts\Domain\Service;

use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Cart;

final class GetCarts
{
    public function __construct(
        private readonly CartRepository $cartRepository
    ) {
    }

    /** @return Cart[] */
    public function __invoke(): array
    {
        return $this->cartRepository->findAll();
    }
}
