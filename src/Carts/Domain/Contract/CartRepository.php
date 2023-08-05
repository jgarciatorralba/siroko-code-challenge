<?php

declare(strict_types=1);

namespace App\Carts\Domain\Contract;

use App\Carts\Domain\Cart;
use App\Shared\Domain\ValueObject\Uuid;

interface CartRepository
{
    public function create(Cart $cart): void;

    public function update(Cart $cart): void;

    public function delete(Cart $cart): void;

    /** @return Cart[] */
    public function findAll(): array;

    public function findOneById(Uuid $id): Cart|null;

    /**
     * @param array <string, mixed> $criteria
     * @param array <string, string>|null $orderBy
     * @return Cart[]
     */
    public function findByCriteria(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;
}
