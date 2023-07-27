<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use DateTimeInterface;

trait Timestampable
{
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;
    private ?DateTimeInterface $deletedAt = null;

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function updateCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function updateUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function updateDeletedAt(?DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
