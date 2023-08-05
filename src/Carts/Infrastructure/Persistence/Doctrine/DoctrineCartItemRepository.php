<?php

declare(strict_types=1);

namespace App\Carts\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Carts\Domain\Contract\CartItemRepository;
use App\Carts\Domain\CartItem;
use DateTimeImmutable;

class DoctrineCartItemRepository extends DoctrineRepository implements CartItemRepository
{
    protected function entityClass(): string
    {
        return CartItem::class;
    }

    public function create(CartItem $cartItem): void
    {
        $this->persist($cartItem);
    }

    public function update(CartItem $cartItem): void
    {
        $this->updateEntity();
    }

    public function delete(CartItem $cartItem): void
    {
        $cartItem->updateDeletedAt(new DateTimeImmutable());
        $this->updateEntity();
    }

    /**
     * @return CartItem[]
     */
    public function findAll(): array
    {
        return $this->repository()->findAll();
    }

    public function findOneById(Uuid $id): CartItem|null
    {
        return $this->repository()->findOneBy(['id' => $id->value()]);
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     * @return CartItem[]
     */
    public function findByCriteria(
        array $criteria = [],
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        return $this->repository()->findBy($criteria, $orderBy, $limit, $offset);
    }
}
