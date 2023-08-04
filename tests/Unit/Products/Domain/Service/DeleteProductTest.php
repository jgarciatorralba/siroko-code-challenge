<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Products\Domain\Service\DeleteProduct;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should delete a product', function () {
    $id = Uuid::random();
    $product = Product::create(
        id: $id,
        name: 'delete-product-unit-test',
        price: 1.23,
        createdAt: new DateTimeImmutable(),
        updatedAt: new DateTimeImmutable()
    );

    $this->productRepositoryMock->shouldDeleteProduct($product);

    $service = new DeleteProduct(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke($product);

    expect($result)->toBeEmpty();
});
