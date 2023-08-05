<?php

declare(strict_types=1);

namespace App\Carts\Domain\Contract;

use App\Carts\Domain\CartItem;
use App\Shared\Domain\ValueObject\Uuid;

interface CartItemRepository
{
    public function create(CartItem $cartItem): void;

    public function update(CartItem $cartItem): void;

    public function delete(CartItem $cartItem): void;

    /** @return CartItem[] */
    public function findAll(): array;

    public function findOneById(Uuid $id): CartItem|null;

    /**
     * @param array <string, mixed> $criteria
     * @param array <string, string>|null $orderBy
     * @return CartItem[]
     */
    public function findByCriteria(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;
}
