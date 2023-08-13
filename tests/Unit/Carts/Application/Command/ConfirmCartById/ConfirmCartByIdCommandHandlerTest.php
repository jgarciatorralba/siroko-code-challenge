<?php

declare(strict_types=1);

use App\Carts\Application\Command\ConfirmCartById\ConfirmCartByIdCommandHandler;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\CartNotFoundException;
use App\Carts\Domain\Exception\EmptyCartException;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\Application\Command\ConfirmCartById\ConfirmCartByIdCommandMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\ConfirmCartMock;
use App\Tests\Unit\Carts\TestCase\GetCartByIdMock;

beforeEach(function () {
    $this->getCartByIdMock = new GetCartByIdMock($this);
    $this->confirmCartMock = new ConfirmCartMock($this);
});

it('should confirm a cart', function () {
    $cart = CartMother::create(null, null, false, null, null);
    $command = ConfirmCartByIdCommandMother::createFromCart($cart);

    $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
    $this->confirmCartMock->shouldConfirmCart($cart);

    $handler = new ConfirmCartByIdCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        confirmCart: $this->confirmCartMock->getMock()
    );
    $handler->__invoke($command);
});

it('should throw an exception if a cart is not found', function () {
    $id = Uuid::random();
    $command = ConfirmCartByIdCommandMother::create($id->value());

    $this->getCartByIdMock->shouldThrowException($id);

    $handler = new ConfirmCartByIdCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        confirmCart: $this->confirmCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(CartNotFoundException::class);

it('should throw an exception if a cart is empty', function () {
    $cart = CartMother::create(null, null, true, null, null);
    $command = ConfirmCartByIdCommandMother::create($cart->id()->value());

    $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
    $this->confirmCartMock->shouldThrowEmptyCartException($cart);

    $handler = new ConfirmCartByIdCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        confirmCart: $this->confirmCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(EmptyCartException::class);

it('should throw an exception if a cart is already confirmed', function () {
    $cart = CartMother::create(null, null, true, null, null);
    $command = ConfirmCartByIdCommandMother::createFromCart($cart);

    $this->getCartByIdMock->shouldReturnCart($cart->id(), $cart);
    $this->confirmCartMock->shouldThrowCartAlreadyConfirmedException($cart);

    $handler = new ConfirmCartByIdCommandHandler(
        getCartById: $this->getCartByIdMock->getMock(),
        confirmCart: $this->confirmCartMock->getMock()
    );
    $handler->__invoke($command);
})->throws(CartAlreadyConfirmedException::class);
