<?php

declare(strict_types=1);

namespace App\Carts\Application\Query\GetCartById;

use App\Carts\Domain\Service\GetCartById;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class GetCartByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetCartById $getCartById
    ) {
    }

    public function __invoke(GetCartByIdQuery $query): GetCartByIdResponse
    {
        $cart = $this->getCartById->__invoke(
            Uuid::fromString($query->id())
        );

        return new GetCartByIdResponse($cart->toArray());
    }
}
