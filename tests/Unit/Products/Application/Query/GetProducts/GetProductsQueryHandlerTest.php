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
    $products = [
        Product::create(
            Uuid::random(),
            'test-product-1',
            10.00
        ),
        Product::create(
            Uuid::random(),
            'test-product-2',
            55.55
        ),
        Product::create(
            Uuid::random(),
            'test-product-3',
            1.23
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
