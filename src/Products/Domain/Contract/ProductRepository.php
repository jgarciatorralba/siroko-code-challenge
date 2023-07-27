<?php

declare(strict_types=1);

namespace App\Products\Domain\Contract;

use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;

interface ProductRepository
{
    public function create(Product $product): void;

    public function update(Product $product): void;

    public function delete(Product $product): void;

    /** @return Product[] */
    public function findAll(): array;

    public function findOneById(Uuid $id): Product|null;

    /**
     * @param array <string, mixed> $criteria
     * @param array <string, string>|null $orderBy
     * @return Product[]
     */
    public function findByCriteria(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;
}
