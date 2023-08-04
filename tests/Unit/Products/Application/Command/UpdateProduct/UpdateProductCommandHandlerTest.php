<?php

declare(strict_types=1);

use App\Products\Application\Command\UpdateProduct\UpdateProductCommandHandler;
use App\Products\Domain\Exception\ProductNotFoundException;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Application\Command\UpdateProduct\UpdateProductCommandMother;
use App\Tests\Unit\Products\TestCase\GetProductByIdMock;
use App\Tests\Unit\Products\TestCase\UpdateProductMock;

beforeEach(function () {
    $this->updateProductMock = new UpdateProductMock($this);
    $this->getProductByIdMock = new GetProductByIdMock($this);
});

it('should update a product', function () {
    $now = new DateTimeImmutable();
    $product = Product::create(
        Uuid::random(),
        'update-product-unit-test',
        7.77,
        $now,
        $now
    );
    $command = UpdateProductCommandMother::createFromProduct($product);

    $this->getProductByIdMock->shouldReturnProduct($product->id(), $product);
    $this->updateProductMock->shouldUpdateProduct($product);

    $handler = new UpdateProductCommandHandler(
        getProductById: $this->getProductByIdMock->getMock(),
        updateProduct: $this->updateProductMock->getMock()
    );
    $handler->__invoke($command);
});

it('should throw an exception if a product is not found', function () {
    $now = new DateTimeImmutable();
    $product = Product::create(
        Uuid::random(),
        'update-product-unit-test',
        7.77,
        $now,
        $now
    );
    $command = UpdateProductCommandMother::createFromProduct($product);

    $this->getProductByIdMock->shouldThrowException($product->id());

    $handler = new UpdateProductCommandHandler(
        getProductById: $this->getProductByIdMock->getMock(),
        updateProduct: $this->updateProductMock->getMock()
    );
    $handler->__invoke($command);
})->throws(ProductNotFoundException::class);
