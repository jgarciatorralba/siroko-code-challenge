<?php

declare(strict_types=1);

use App\Products\Application\Query\GetProductById\GetProductByIdQueryHandler;
use App\Products\Domain\Exception\ProductNotFoundException;
use App\Tests\Unit\Products\Application\Query\GetProductById\GetProductByIdQueryMother;
use App\Tests\Unit\Products\Application\Query\GetProductById\GetProductByIdResponseMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\GetProductByIdMock;

beforeEach(function () {
    $this->getProductById = new GetProductByIdMock($this);
});

it('should return a normalized product given its id', function () {
    $product = ProductMother::create();

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

it('should throw an exception if a product is not found', function () {
    $product = ProductMother::create();

    $query = GetProductByIdQueryMother::create(
        id: $product->id()->value()
    );
    $response = GetProductByIdResponseMother::create($product);

    $this->getProductById->shouldThrowException($product->id());

    $handler = new GetProductByIdQueryHandler(
        getProductById: $this->getProductById->getMock()
    );
    $result = $handler->__invoke($query);

    expect($result)->toBeQueryResponse($response->data());
})->throws(ProductNotFoundException::class);
