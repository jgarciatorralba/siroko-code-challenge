<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Application\Query\GetCartById;

use App\Carts\Application\Query\GetCartById\GetCartByIdResponse;
use App\Carts\Domain\Cart;

final class GetCartByIdResponseMother
{
    public static function create(Cart $cart): GetCartByIdResponse
    {
        return new GetCartByIdResponse($cart->toArray());
    }
}
