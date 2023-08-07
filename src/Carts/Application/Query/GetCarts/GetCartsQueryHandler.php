<?php

declare(strict_types=1);

namespace App\Carts\Application\Query\GetCarts;

use App\Carts\Domain\Service\GetCarts;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetCartsQueryHandler implements QueryHandler
{
    public function __construct(private readonly GetCarts $getCarts)
    {
    }

    public function __invoke(GetCartsQuery $query): GetCartsResponse
    {
        $carts = $this->getCarts->__invoke();
        $carts = array_map(fn (AggregateRoot $cart) => $cart->toArray(), $carts);

        return new GetCartsResponse($carts);
    }
}
