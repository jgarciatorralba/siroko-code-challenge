<?php

declare(strict_types=1);

use App\Products\Domain\Service\UpdateProduct;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should update a product', function () {
    $product = ProductMother::create();

    $this->productRepositoryMock->shouldUpdateProduct($product);

    $service = new UpdateProduct(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke($product, [
        'name' => FakeValueGenerator::string(),
        'price' => FakeValueGenerator::float(1, 100),
        'updatedAt' => FakeValueGenerator::dateTime()
    ]);

    expect($result)->toBeEmpty();
});
