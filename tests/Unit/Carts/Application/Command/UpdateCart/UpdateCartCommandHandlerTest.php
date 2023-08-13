<?php

declare(strict_types=1);

use App\Carts\Application\Command\UpdateCart\UpdateCartCommandHandler;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\CartItemAlreadyExistingException;
use App\Carts\Domain\Exception\CartItemNotFoundException;
use App\Carts\Domain\Exception\CartNotFoundException;
use App\Products\Domain\Exception\ProductNotFoundException;
use App\Tests\Unit\Carts\Application\Command\UpdateCart\UpdateCartCommandMother;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartItemOperationMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\GetCartByIdMock;
use App\Tests\Unit\Carts\TestCase\UpdateCartMock;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Products\TestCase\GetMappedProductsByIdMock;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

beforeEach(function () {
    $this->getCartByIdMock = new GetCartByIdMock($this);
    $this->getMappedProductsByIdMock = new GetMappedProductsByIdMock($this);
    $this->updateCartMock = new UpdateCartMock($this);
});

it('should update a cart', function () {
    $cart = CartMother::create(null, null, false, null, null);
    $product = ProductMother::create();
    $cartItem = CartItemMother::create(
        null,
        $cart,
        $product,
        null,
        null,
        $cart->createdAt(),
        $cart->updatedAt()
    );

    $cart->addItem($cartItem);

    $cartItemOperation = CartItemOperationMother::create(
        'update',
        $product,
        null,
        null,
        null
    );

    $command = UpdateCartCommandMother::create(
        $cart->id()->value(),
        [
            [
                'operation' => $cartItemOperation->type(),
                'productId' => $cartItemOperation->product()->id()->value(),
                'quantity' => $cartItemOperation->quantity()
            ]
        ],
        $cartItemOperation->dateTime()
    );

    $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
    $this->getMappedProductsByIdMock->shouldReturnMappedProducts(
        [$product->id()],
        [$product->id()->value() => $product]
    );
    $this->updateCartMock->shouldUpdateCart($cart, [
        'updatedAt' => $command->updatedAt(),
        'itemOperations' => [$cartItemOperation]
    ]);

    $handler = new UpdateCartCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        getMappedProductsById: $this->getMappedProductsByIdMock->getMock(),
        updateCart: $this->updateCartMock->getMock()
    );
    $handler->__invoke($command);
});

it('should throw an exception if a cart is not found', function () {
    $id = FakeValueGenerator::uuid();

    $command = UpdateCartCommandMother::create(
        $id->value(),
        [
            [
                'operation' => FakeValueGenerator::randomElement(['add', 'update', 'remove']),
                'productId' => FakeValueGenerator::uuid()->value(),
                'quantity' => FakeValueGenerator::integer(1, 10)
            ]
        ]
    );

    $this->getCartByIdMock->shouldThrowException($id);

    $handler = new UpdateCartCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        getMappedProductsById: $this->getMappedProductsByIdMock->getMock(),
        updateCart: $this->updateCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(CartNotFoundException::class);

it('should throw an exception if a cart is already confirmed', function () {
    $cart = CartMother::create(null, null, true, null, null);
    $product = ProductMother::create();
    $cartItem = CartItemMother::create(
        null,
        $cart,
        $product,
        null,
        null,
        $cart->createdAt(),
        $cart->updatedAt()
    );

    $cart->addItem($cartItem);

    $command = UpdateCartCommandMother::create(
        $cart->id()->value(),
        [
            [
                'operation' => FakeValueGenerator::randomElement(['add', 'update', 'remove']),
                'productId' => $product->id()->value(),
                'quantity' => FakeValueGenerator::integer(1, 10)
            ]
        ]
    );

    $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
    $this->getMappedProductsByIdMock->shouldReturnMappedProducts(
        [$product->id()],
        [$product->id()->value() => $product]
    );
    $this->updateCartMock->shouldThrowCartAlreadyConfirmedException($cart);

    $handler = new UpdateCartCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        getMappedProductsById: $this->getMappedProductsByIdMock->getMock(),
        updateCart: $this->updateCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(CartAlreadyConfirmedException::class);

it('should throw an exception if a product is not found', function () {
    $cart = CartMother::create(null, null, true, null, null);
    $product = ProductMother::create();

    $command = UpdateCartCommandMother::create(
        $cart->id()->value(),
        [
            [
                'operation' => FakeValueGenerator::randomElement(['add', 'update', 'remove']),
                'productId' => $product->id()->value(),
                'quantity' => FakeValueGenerator::integer(1, 10)
            ]
        ]
    );

    $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
    $this->getMappedProductsByIdMock->shouldThrowException([$product->id()]);

    $handler = new UpdateCartCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        getMappedProductsById: $this->getMappedProductsByIdMock->getMock(),
        updateCart: $this->updateCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(ProductNotFoundException::class);

it(
    'should throw an exception if an item already exists for a product on "add" operation',
    function () {
        $cart = CartMother::create(null, null, false, null, null);
        $product = ProductMother::create();
        $cartItem = CartItemMother::create(
            null,
            $cart,
            $product,
            null,
            null,
            $cart->createdAt(),
            $cart->updatedAt()
        );

        $cart->addItem($cartItem);

        $command = UpdateCartCommandMother::create(
            $cart->id()->value(),
            [
                [
                    'operation' => 'add',
                    'productId' => $product->id()->value(),
                    'quantity' => FakeValueGenerator::integer(1, 10)
                ]
            ]
        );

        $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
        $this->getMappedProductsByIdMock->shouldReturnMappedProducts(
            [$product->id()],
            [$product->id()->value() => $product]
        );
        $this->updateCartMock->shouldThrowCartItemAlreadyExistingException($cart, $product);

        $handler = new UpdateCartCommandHandler(
            getCartById: $this->getCartByIdMock->getMock(),
            getMappedProductsById: $this->getMappedProductsByIdMock->getMock(),
            updateCart: $this->updateCartMock->getMock()
        );
        $handler->__invoke($command);
    }
)->throws(CartItemAlreadyExistingException::class);

it(
    'should throw an exception if an item doesn\'t exist for a product on "update" or "remove" operation',
    function () {
        $cart = CartMother::create(null, null, false, null, null);
        $product = ProductMother::create();
        $cartItem = CartItemMother::create(
            null,
            $cart,
            $product,
            null,
            null,
            $cart->createdAt(),
            $cart->updatedAt()
        );

        $cart->addItem($cartItem);

        $command = UpdateCartCommandMother::create(
            $cart->id()->value(),
            [
                [
                    'operation' => FakeValueGenerator::randomElement(['update', 'remove']),
                    'productId' => $product->id()->value(),
                    'quantity' => FakeValueGenerator::integer(1, 10)
                ]
            ]
        );

        $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
        $this->getMappedProductsByIdMock->shouldReturnMappedProducts(
            [$product->id()],
            [$product->id()->value() => $product]
        );
        $this->updateCartMock->shouldThrowCartItemNotFoundException($cart, $product);

        $handler = new UpdateCartCommandHandler(
            getCartById: $this->getCartByIdMock->getMock(),
            getMappedProductsById: $this->getMappedProductsByIdMock->getMock(),
            updateCart: $this->updateCartMock->getMock()
        );
        $handler->__invoke($command);
    }
)->throws(CartItemNotFoundException::class);
