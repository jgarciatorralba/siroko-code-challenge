<?php

declare(strict_types=1);

namespace App\Tests\Unit\Products\Application\Query\GetProductById;

use App\Products\Application\Query\GetProductById\GetProductByIdResponse;
use App\Products\Domain\Product;

final class GetProductByIdResponseMother
{
    public static function create(Product $product): GetProductByIdResponse
    {
        return new GetProductByIdResponse($product->toArray());
    }
}
