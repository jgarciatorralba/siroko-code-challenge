<?php

declare(strict_types=1);

use App\Carts\Domain\Cart;
use App\Carts\Domain\Service\GetCarts;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\TestCase\CartRepositoryMock;

beforeEach(function () {
    $this->cartRepositoryMock = new CartRepositoryMock($this);
});

it('should return an array of carts', function () {
    $carts = [
        Cart::create(
            Uuid::random(),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        ),
        Cart::create(
            Uuid::random(),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        ),
        Cart::create(
            Uuid::random(),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        )
    ];

    $this->cartRepositoryMock->shouldFindCarts($carts);

    $service = new GetCarts(
        cartRepository: $this->cartRepositoryMock->getMock()
    );
    $result = $service->__invoke();

    expect($result)->toEqual($carts);
});

it('should return an empty array', function () {
    $this->cartRepositoryMock->shouldNotFindCarts();

    $service = new GetCarts(
        cartRepository: $this->cartRepositoryMock->getMock()
    );
    $result = $service->__invoke();

    expect($result)->toEqual([]);
});
