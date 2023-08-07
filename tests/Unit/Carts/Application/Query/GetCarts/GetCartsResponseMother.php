<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Application\Query\GetCarts;

use App\Carts\Application\Query\GetCarts\GetCartsResponse;
use App\Shared\Domain\Aggregate\AggregateRoot;

final class GetCartsResponseMother
{
    /**
     * @param array<AggregateRoot> $carts
     */
    public static function create(array $carts): GetCartsResponse
    {
        $carts = array_map(fn (AggregateRoot $cart) => $cart->toArray(), $carts);
        return new GetCartsResponse($carts);
    }
}
