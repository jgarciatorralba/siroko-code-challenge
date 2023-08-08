<?php

declare(strict_types=1);

namespace App\Carts\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Carts\Domain\Contract\CartRepository;
use App\Carts\Domain\Cart;
use DateTimeImmutable;

class DoctrineCartRepository extends DoctrineRepository implements CartRepository
{
    protected function entityClass(): string
    {
        return Cart::class;
    }

    public function create(Cart $cart): void
    {
        $this->persist($cart);
    }

    public function update(Cart $cart): void
    {
        $this->updateEntity();
    }

    public function delete(Cart $cart): void
    {
        $now = new DateTimeImmutable();

        $cart->updateDeletedAt($now);
        foreach ($cart->items() as $item) {
            $item->updateDeletedAt($now);
        }

        $this->updateEntity();
    }

    /**
     * @return Cart[]
     */
    public function findAll(): array
    {
        return $this->repository()->findAll();
    }

    public function findOneById(Uuid $id): Cart|null
    {
        return $this->repository()->findOneBy(['id' => $id->value()]);
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     * @return Cart[]
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
