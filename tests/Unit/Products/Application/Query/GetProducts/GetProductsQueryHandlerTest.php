<?php

declare(strict_types=1);

use App\Products\Application\Query\GetProducts\GetProductsQuery;
use App\Products\Application\Query\GetProducts\GetProductsQueryHandler;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Application\Query\GetProducts\GetProductsResponseMother;
use App\Tests\Unit\Products\TestCase\GetProductsMock;

beforeEach(function () {
    $this->getProductsMock = new GetProductsMock($this);
});

it('should return an array of normalized products', function () {
    $now = new DateTimeImmutable();

    $products = [
        Product::create(
            Uuid::random(),
            'get-products-unit-test-1',
            10.00,
            $now,
            $now
        ),
        Product::create(
            Uuid::random(),
            'get-products-unit-test-2',
            55.55,
            $now,
            $now
        ),
        Product::create(
            Uuid::random(),
            'get-products-unit-test-3',
            1.23,
            $now,
            $now
        )
    ];

    $this->getProductsMock->shouldGetAllProducts($products);

    $handler = new GetProductsQueryHandler(
        getProducts: $this->getProductsMock->getMock()
    );
    $result = $handler->__invoke(new GetProductsQuery());

    $response = GetProductsResponseMother::create($products);
    expect($result)->toBeQueryResponse($response->data());
});
