<?php

declare(strict_types=1);

namespace App\Carts\Domain\Service;

use App\Carts\Domain\Cart;
use App\Carts\Domain\CartItem;
use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Exception\CartAlreadyConfirmedException;
use App\Carts\Domain\Exception\CartItemAlreadyExistingException;
use App\Carts\Domain\Exception\CartItemNotFoundException;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;

final class UpdateCart
{
    private const OPERATION_ADD = 'add';
    private const OPERATION_UPDATE = 'update';
    private const OPERATION_REMOVE = 'remove';

    public function __construct(
        private readonly CartRepository $cartRepository
    ) {
    }

    /**
     * @param array<string, mixed> $updatedData
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

    /**
     * @param array<string, string|int|Product|DateTimeImmutable> $operation
     */
    private function processOperation(Cart $cart, array $operation): void
    {
        switch ($operation['operation']) :
            case self::OPERATION_ADD:
                $cartItem = $cart->getItemByProductId($operation['product']->id());
                if (!empty($cartItem)) {
                    throw new CartItemAlreadyExistingException(
                        $cartItem->product()->id(),
                        $cartItem->cart()->id()
                    );
                }

                $cartItem = CartItem::create(
                    Uuid::random(),
                    $cart,
                    $operation['product'],
                    $operation['quantity'],
                    $operation['createdAt'],
                    $operation['updatedAt']
                );

                $cart->addItem($cartItem);

                break;
            case self::OPERATION_UPDATE:
                $cartItem = $cart->getItemByProductId($operation['product']->id());
                if (empty($cartItem)) {
                    throw new CartItemNotFoundException(
                        $operation['product']->id(),
                        $cart->id()
                    );
                }

                $cartItem->updateQuantity($operation['quantity']);
                $cartItem->updateUpdatedAt($operation['updatedAt']);

                break;
            case self::OPERATION_REMOVE:
                $cartItem = $cart->getItemByProductId($operation['product']->id());
                if (empty($cartItem)) {
                    throw new CartItemNotFoundException(
                        $operation['product']->id(),
                        $cart->id()
                    );
                }

                $cartItem->updateDeletedAt($operation['deletedAt']);

                break;
        endswitch;
    }
}
