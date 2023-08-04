<?php

declare(strict_types=1);

namespace App\Products\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Products\Domain\Contract\ProductRepository;
use App\Products\Domain\Product;
use DateTimeImmutable;

class DoctrineProductRepository extends DoctrineRepository implements ProductRepository
{
    protected function entityClass(): string
    {
        return Product::class;
    }

    public function create(Product $product): void
    {
        $this->persist($product);
    }

    public function update(Product $product): void
    {
        $this->updateEntity();
    }

    public function delete(Product $product): void
    {
        $product->updateDeletedAt(new DateTimeImmutable());
        $this->updateEntity();
    }

    /**
     * @return Product[]
     */
    public function findAll(): array
    {
        return $this->repository()->findAll();
    }

    public function findOneById(Uuid $id): Product|null
    {
        return $this->repository()->findOneBy(['id' => $id->value()]);
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     * @return Product[]
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
