<?php

declare(strict_types=1);

namespace App\Carts\Domain\Service;

use App\Shared\Domain\ValueObject\Uuid;
use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Exception\CartNotFoundException;
use App\Carts\Domain\Cart;

final class GetCartById
{
    public function __construct(
        private readonly CartRepository $cartRepository
    ) {
    }

    public function __invoke(Uuid $id): Cart
    {
        $cart = $this->cartRepository->findOneById($id);
        if ($cart === null) {
            throw new CartNotFoundException($id);
        }

        return $cart;
    }
}
