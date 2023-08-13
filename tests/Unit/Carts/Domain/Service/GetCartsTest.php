<?php

declare(strict_types=1);

use App\Carts\Domain\Service\GetCarts;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Carts\TestCase\CartRepositoryMock;

beforeEach(function () {
    $this->cartRepositoryMock = new CartRepositoryMock($this);
});

it('should return an array of carts', function () {
    $carts = [
        CartMother::create(),
        CartMother::create(),
        CartMother::create()
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
