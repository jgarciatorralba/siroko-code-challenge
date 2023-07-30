<?php

declare(strict_types=1);

use App\Products\Application\Query\GetProductById\GetProductByIdQueryHandler;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Application\Query\GetProductById\GetProductByIdQueryMother;
use App\Tests\Unit\Products\Application\Query\GetProductById\GetProductByIdResponseMother;
use App\Tests\Unit\Products\TestCase\GetProductByIdMock;

beforeEach(function () {
    $this->getProductById = new GetProductByIdMock($this);
});

it('should find a product given its id', function () {
    $product = Product::create(
        id: Uuid::random(),
        name: 'test-product',
        price: 1.23
    );

    $query = GetProductByIdQueryMother::create(
        id: $product->id()->value()
    );
    $response = GetProductByIdResponseMother::create($product);

    $this->getProductById->shouldReturnProduct($product->id(), $product);

    $handler = new GetProductByIdQueryHandler(
        getProductById: $this->getProductById->getMock()
    );
    $result = $handler->__invoke($query);

    expect($result)->toBeQueryResponse($response->data());
});
