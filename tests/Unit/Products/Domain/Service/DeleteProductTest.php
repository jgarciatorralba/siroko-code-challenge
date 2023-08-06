<?php

declare(strict_types=1);

use App\Carts\Domain\Cart;
use App\Carts\Domain\CartItem;
use App\Products\Domain\Exception\ProductInUseException;
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

it('should throw an exception if a product is referenced by a cart item', function () {
    $now = new DateTimeImmutable();
    $product = Product::create(
        Uuid::random(),
        'delete-product-unit-test',
        10.10,
        $now,
        $now
    );

    $cart = new Cart(
        Uuid::random(),
        null,
        false,
        new DateTimeImmutable(),
        new DateTimeImmutable()
    );

    $cartItem = new CartItem(
        Uuid::random(),
        $cart,
        $product,
        1,
        new DateTimeImmutable(),
        new DateTimeImmutable()
    );
    $product->addCartItem($cartItem);

    $this->productRepositoryMock->shouldNotDeleteProduct($product);

    $service = new DeleteProduct(
        productRepository: $this->productRepositoryMock->getMock()
    );
    $service->__invoke($product);
})->throws(ProductInUseException::class);
