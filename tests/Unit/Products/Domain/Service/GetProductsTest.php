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
