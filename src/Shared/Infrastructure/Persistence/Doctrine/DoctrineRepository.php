<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Aggregate\AggregateRoot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class DoctrineRepository
{
    /**
     * @var EntityRepository<object> $repository
     */
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        /** @var class-string $className */
        $className = $this->entityClass();
        $this->repository = $entityManager->getRepository($className);
    }

    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return EntityRepository<object>
     */
    protected function repository(): EntityRepository
    {
        return $this->repository;
    }

    protected function persist(AggregateRoot $entity): void
    {
        $this->entityManager()->persist($entity);
        $this->entityManager()->flush();
    }

    protected function remove(AggregateRoot $entity): void
    {
        $this->entityManager()->remove($entity);
        $this->entityManager()->flush();
    }

    protected function updateEntity(): void
    {
        $this->entityManager()->flush();
    }

    abstract protected function entityClass(): string;
}
