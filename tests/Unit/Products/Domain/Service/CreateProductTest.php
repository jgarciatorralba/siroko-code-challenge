<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Products\Domain\Service\CreateProduct;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should create a product', function () {
    $product = Product::create(
        Uuid::random(),
        'test-product-create',
        10.00,
        new DateTimeImmutable(),
        new DateTimeImmutable()
    );

    $this->productRepositoryMock->shouldCreateProduct($product);

    $service = new CreateProduct(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke($product);

    expect($result)->toEqual(null);
});
