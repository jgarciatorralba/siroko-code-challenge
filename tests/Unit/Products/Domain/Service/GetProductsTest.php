<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Products\Domain\Service\GetProducts;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should return an array of products', function () {
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
