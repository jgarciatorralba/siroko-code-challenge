<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\Application\Query\GetProducts;

use App\Products\Application\Query\GetProducts\GetProductsResponse;
use App\Shared\Domain\Aggregate\AggregateRoot;

final class GetProductsResponseMother
{
    /**
     * @param array<AggregateRoot> $products
     */
    public static function create(array $products): GetProductsResponse
    {
        $products = array_map(fn (AggregateRoot $product) => $product->toArray(), $products);
        return new GetProductsResponse($products);
    }
}
