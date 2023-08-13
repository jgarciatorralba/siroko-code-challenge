<?php

declare(strict_types=1);

use App\Products\Application\Query\GetProducts\GetProductsQuery;
use App\Products\Application\Query\GetProducts\GetProductsQueryHandler;
use App\Tests\Unit\Products\Application\Query\GetProducts\GetProductsResponseMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\GetProductsMock;

beforeEach(function () {
    $this->getProductsMock = new GetProductsMock($this);
});

it('should return an array of normalized products', function () {
    $products = [
        ProductMother::create(),
        ProductMother::create(),
        ProductMother::create()
    ];

    $this->getProductsMock->shouldGetAllProducts($products);

    $handler = new GetProductsQueryHandler(
        getProducts: $this->getProductsMock->getMock()
    );
    $result = $handler->__invoke(new GetProductsQuery());

    $response = GetProductsResponseMother::create($products);
    expect($result)->toBeQueryResponse($response->data());
});
