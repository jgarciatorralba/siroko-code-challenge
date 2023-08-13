<?php

declare(strict_types=1);

use App\Carts\Application\Command\DeleteCartById\DeleteCartByIdCommandHandler;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\CartNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\Application\Command\DeleteCartById\DeleteCartByIdCommandMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\DeleteCartMock;
use App\Tests\Unit\Carts\TestCase\GetCartByIdMock;

beforeEach(function () {
    $this->getCartByIdMock = new GetCartByIdMock($this);
    $this->deleteCartMock = new DeleteCartMock($this);
});

it('should delete a cart', function () {
    $cart = CartMother::create(null, null, false, null, null);
    $command = DeleteCartByIdCommandMother::createFromCart($cart);

    $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
    $this->deleteCartMock->shouldDeleteCart($cart);

    $handler = new DeleteCartByIdCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        deleteCart: $this->deleteCartMock->getMock()
    );
    $handler->__invoke($command);
});

it('should throw an exception if a cart is not found', function () {
    $id = Uuid::random();
    $command = DeleteCartByIdCommandMother::create($id->value());

    $this->getCartByIdMock->shouldThrowException($id);

    $handler = new DeleteCartByIdCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        deleteCart: $this->deleteCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(CartNotFoundException::class);

it('should throw an exception if a cart is already confirmed', function () {
    $cart = CartMother::create(null, null, true, null, null);
    $command = DeleteCartByIdCommandMother::createFromCart($cart);

    $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
    $this->deleteCartMock->shouldThrowException($cart);

    $handler = new DeleteCartByIdCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        deleteCart: $this->deleteCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(CartAlreadyConfirmedException::class);
