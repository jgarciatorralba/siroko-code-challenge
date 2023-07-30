<?php

declare(strict_types=1);

namespace App\Products\Application\Query\GetProductById;

use App\Products\Domain\Service\GetProductById;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class GetProductByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetProductById $getProductById
    ) {
    }

    public function __invoke(GetProductByIdQuery $query): GetProductByIdResponse
    {
        $product = $this->getProductById->__invoke(
            Uuid::fromString($query->id())
        );

        return new GetProductByIdResponse($product->toArray());
    }
}
