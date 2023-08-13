<?php

declare(strict_types=1);

use App\Products\Domain\Service\CreateProduct;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should create a product', function () {
    $product = ProductMother::create();

    $this->productRepositoryMock->shouldCreateProduct($product);

    $service = new CreateProduct(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke($product);

    expect($result)->toEqual(null);
});
