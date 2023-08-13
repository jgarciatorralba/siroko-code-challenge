<?php

declare(strict_types=1);

use App\Products\Application\Command\UpdateProduct\UpdateProductCommandHandler;
use App\Products\Domain\Exception\ProductNotFoundException;
use App\Tests\Unit\Products\Application\Command\UpdateProduct\UpdateProductCommandMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\GetProductByIdMock;
use App\Tests\Unit\Products\TestCase\UpdateProductMock;

beforeEach(function () {
    $this->updateProductMock = new UpdateProductMock($this);
    $this->getProductByIdMock = new GetProductByIdMock($this);
});

it('should update a product', function () {
    $product = ProductMother::create();
    $command = UpdateProductCommandMother::createFromProduct($product);

    $this->getProductByIdMock->shouldReturnProduct($product->id(), $product);
    $this->updateProductMock->shouldUpdateProduct($product, [
        'name' => $command->name(),
        'price' => $command->price(),
        'updatedAt' => $command->updatedAt()
    ]);

    $handler = new UpdateProductCommandHandler(
        getProductById: $this->getProductByIdMock->getMock(),
        updateProduct: $this->updateProductMock->getMock()
    );
    $handler->__invoke($command);
});

it('should throw an exception if a product is not found', function () {
    $product = ProductMother::create();
    $command = UpdateProductCommandMother::createFromProduct($product);

    $this->getProductByIdMock->shouldThrowException($product->id());

    $handler = new UpdateProductCommandHandler(
        getProductById: $this->getProductByIdMock->getMock(),
        updateProduct: $this->updateProductMock->getMock()
    );
    $handler->__invoke($command);
})->throws(ProductNotFoundException::class);
