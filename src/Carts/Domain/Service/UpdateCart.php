<?php

declare(strict_types=1);

namespace App\Carts\Domain\Service;

use App\Carts\Domain\Cart;
use App\Carts\Domain\CartItem;
use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\CartItemAlreadyExistingException;
use App\Carts\Domain\Exception\CartItemNotFoundException;
use App\Carts\Domain\ValueObject\CartItemOperation;
use App\Carts\Domain\ValueObject\OperationType;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;

final class UpdateCart
{
    public function __construct(
        private readonly CartRepository $cartRepository
    ) {
    }

    /**
     * @param array<string, DateTimeImmutable|array<CartItemOperation>> $updatedData
     */
    public function __invoke(Cart $cart, array $updatedData): void
    {
        if ($cart->isConfirmed()) {
            throw new CartAlreadyConfirmedException($cart->id());
        }

        foreach ($updatedData['itemOperations'] as $operation) {
            $this->processOperation($cart, $operation);
        }

        $cart->updateUpdatedAt($updatedData['updatedAt']);
        $this->cartRepository->update($cart);
    }

    private function processOperation(Cart $cart, CartItemOperation $operation): void
    {
        switch ($operation->type()) :
            case OperationType::ADD->value:
                $cartItem = $cart->getItemByProductId($operation->product()->id());
                if (!empty($cartItem)) {
                    throw new CartItemAlreadyExistingException(
                        $cartItem->product()->id(),
                        $cartItem->cart()->id()
                    );
                }

                $cartItem = CartItem::create(
                    Uuid::random(),
                    $cart,
                    $operation->product(),
                    $operation->quantity(),
                    $operation->dateTime(),
                    $operation->dateTime()
                );

                $cart->addItem($cartItem);

                break;
            case OperationType::UPDATE->value:
                $cartItem = $cart->getItemByProductId($operation->product()->id());
                if (empty($cartItem)) {
                    throw new CartItemNotFoundException(
                        $operation->product()->id(),
                        $cart->id()
                    );
                }

                $cartItem->updateQuantity($operation->quantity());
                $cartItem->updateUpdatedAt($operation->dateTime());

                break;
            case OperationType::REMOVE->value:
                $cartItem = $cart->getItemByProductId($operation->product()->id());
                if (empty($cartItem)) {
                    throw new CartItemNotFoundException(
                        $operation->product()->id(),
                        $cart->id()
                    );
                }

                $cartItem->updateDeletedAt($operation->dateTime());

                break;
        endswitch;
    }
}
