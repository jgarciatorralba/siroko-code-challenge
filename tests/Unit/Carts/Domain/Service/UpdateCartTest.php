<?php

declare(strict_types=1);

use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\CartItemAlreadyExistingException;
use App\Carts\Domain\Exception\CartItemNotFoundException;
use App\Carts\Domain\Service\UpdateCart;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartItemOperationMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\CartRepositoryMock;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

beforeEach(function () {
    $this->cartRepositoryMock = new CartRepositoryMock($this);
});

it('should update a cart', function () {
    $cart = CartMother::create(null, null, false, null, null);
    $product = ProductMother::create();
    $cartItem = CartItemMother::create(null, $cart, $product, null, null, null, null);

    $cart->addItem($cartItem);

    $this->cartRepositoryMock->shouldUpdateCart($cart);

    $service = new UpdateCart(
        cartRepository: $this->cartRepositoryMock->getMock()
    );

    $updatedData = [
        'updatedAt' => FakeValueGenerator::dateTime(),
        'itemOperations' => [
            CartItemOperationMother::create(
                'update',
                $product,
                null,
                null
            )
        ]
    ];

    $result = $service->__invoke($cart, $updatedData);

    expect($result)->toBeEmpty();
});

it('should throw an exception if a cart is already confirmed', function () {
    $cart = CartMother::create(null, null, true, null, null);
    $product = ProductMother::create();
    $cartItem = CartItemMother::create(null, $cart, $product, null, null, null, null);

    $cart->addItem($cartItem);

    $this->cartRepositoryMock->shouldNotUpdateCart($cart);

    $service = new UpdateCart(
        cartRepository: $this->cartRepositoryMock->getMock()
    );

    $updatedData = [
        'updatedAt' => FakeValueGenerator::dateTime(),
        'itemOperations' => [
            CartItemOperationMother::create(
                'remove',
                $product,
                null,
                null
            )
        ]
    ];

    $service->__invoke($cart, $updatedData);
})->throws(CartAlreadyConfirmedException::class);

it(
    'should throw an exception if an item already exists for a product on "add" operation',
    function () {
        $cart = CartMother::create(null, null, false, null, null);
        $product = ProductMother::create();
        $cartItem = CartItemMother::create(null, $cart, $product, null, null, null, null);

        $cart->addItem($cartItem);

        $this->cartRepositoryMock->shouldNotUpdateCart($cart);

        $service = new UpdateCart(
            cartRepository: $this->cartRepositoryMock->getMock()
        );

        $updatedData = [
            'updatedAt' => FakeValueGenerator::dateTime(),
            'itemOperations' => [
                CartItemOperationMother::create(
                    'add',
                    $product,
                    null,
                    null
                )
            ]
        ];

        $service->__invoke($cart, $updatedData);
    }
)->throws(CartItemAlreadyExistingException::class);

it(
    'should throw an exception if an item doesn\'t exist for a product on "update" or "remove" operation',
    function () {
        $cart = CartMother::create(null, null, false, null, null);
        $product = ProductMother::create();
        $cartItem = CartItemMother::create(null, $cart, $product, null, null, null, null);

        $cart->addItem($cartItem);

        $this->cartRepositoryMock->shouldNotUpdateCart($cart);

        $service = new UpdateCart(
            cartRepository: $this->cartRepositoryMock->getMock()
        );

        $updatedData = [
            'updatedAt' => FakeValueGenerator::dateTime(),
            'itemOperations' => [
                CartItemOperationMother::create(
                    'update',
                    ProductMother::create(),
                    null,
                    null
                )
            ]
        ];

        $service->__invoke($cart, $updatedData);
    }
)->throws(CartItemNotFoundException::class);
