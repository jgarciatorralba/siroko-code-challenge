<?php

declare(strict_types=1);

use App\Products\Domain\Service\GetProducts;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should return an array of products', function () {
    $products = [
        ProductMother::create(),
        ProductMother::create(),
        ProductMother::create()
    ];

    $this->productRepositoryMock->shouldFindProducts($products);

    $service = new GetProducts(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke();

    expect($result)->toEqual($products);
});

it('should return an empty array', function () {
    $this->productRepositoryMock->shouldNotFindProducts();

    $service = new GetProducts(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke();

    expect($result)->toEqual([]);
});
