<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Products\Domain\Service\UpdateProduct;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should update a product', function () {
    $id = Uuid::random();
    $product = Product::create(
        id: $id,
        name: 'update-product-unit-test',
        price: 11.11,
        createdAt: new DateTimeImmutable(),
        updatedAt: new DateTimeImmutable()
    );

    $this->productRepositoryMock->shouldUpdateProduct($product);

    $service = new UpdateProduct(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke($product, [
        'name' => 'updated-name',
        'price' => 22.22,
        'updatedAt' => new DateTimeImmutable()
    ]);

    expect($result)->toBeEmpty();
});
