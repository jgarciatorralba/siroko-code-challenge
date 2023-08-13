<?php

declare(strict_types=1);

use App\Products\Application\Command\DeleteProductById\DeleteProductByIdCommandHandler;
use App\Products\Domain\Exception\ProductInUseException;
use App\Products\Domain\Exception\ProductNotFoundException;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Application\Command\DeleteProductById\DeleteProductByIdCommandMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\DeleteProductMock;
use App\Tests\Unit\Products\TestCase\GetProductByIdMock;

beforeEach(function () {
    $this->getProductByIdMock = new GetProductByIdMock($this);
    $this->deleteProductMock = new DeleteProductMock($this);
});

it('should delete a product', function () {
    $product = ProductMother::create();
    $command = DeleteProductByIdCommandMother::createFromProduct($product);

    $this->getProductByIdMock->shouldReturnProduct($product->id(), $product);
    $this->deleteProductMock->shouldDeleteProduct($product);

    $handler = new DeleteProductByIdCommandHandler(
        getProductById: $this->getProductByIdMock->getMock(),
        deleteProduct: $this->deleteProductMock->getMock()
    );
    $handler->__invoke($command);
});

it('should throw an exception if a product is not found', function () {
    $id = Uuid::random();
    $command = DeleteProductByIdCommandMother::create($id->value());

    $this->getProductByIdMock->shouldThrowException($id);

    $handler = new DeleteProductByIdCommandHandler(
        getProductById: $this->getProductByIdMock->getMock(),
        deleteProduct: $this->deleteProductMock->getMock()
    );
    $handler->__invoke($command);
})->throws(ProductNotFoundException::class);

it('should throw an exception if a product is referenced by a cart item', function () {
    $product = ProductMother::create();
    $command = DeleteProductByIdCommandMother::createFromProduct($product);

    $this->getProductByIdMock->shouldReturnProduct($product->id(), $product);
    $this->deleteProductMock->shouldThrowException($product);

    $handler = new DeleteProductByIdCommandHandler(
        getProductById: $this->getProductByIdMock->getMock(),
        deleteProduct: $this->deleteProductMock->getMock()
    );
    $handler->__invoke($command);
})->throws(ProductInUseException::class);
