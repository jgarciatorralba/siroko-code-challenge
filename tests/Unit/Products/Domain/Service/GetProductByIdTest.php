<?php

declare(strict_types=1);

use App\Products\Domain\Exception\ProductNotFoundException;
use App\Products\Domain\Service\GetProductById;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\ProductRepositoryMock;

beforeEach(function () {
    $this->productRepositoryMock = new ProductRepositoryMock($this);
});

it('should return a product', function () {
    $product = ProductMother::create();

    $this->productRepositoryMock->shouldFindProductById($product->id(), $product);

    $service = new GetProductById(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $result = $service->__invoke($product->id());

    expect($result)->toEqual($product);
});

it('should throw an exception if a product is not found', function () {
    $id = Uuid::random();

    $this->productRepositoryMock->shouldNotFindProductById($id);

    $service = new GetProductById(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $service->__invoke($id);
})->throws(ProductNotFoundException::class);
