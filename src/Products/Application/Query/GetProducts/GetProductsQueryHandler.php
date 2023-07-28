<?php

declare(strict_types=1);

namespace App\Products\Application\Query\GetProducts;

use App\Products\Domain\Service\GetProducts;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetProductsQueryHandler implements QueryHandler
{
    public function __construct(private readonly GetProducts $getProducts)
    {
    }

    public function __invoke(GetProductsQuery $query): GetProductsResponse
    {
        $products = $this->getProducts->__invoke();
        $products = array_map(fn (AggregateRoot $product) => $product->toArray(), $products);

        return new GetProductsResponse($products);
    }
}
