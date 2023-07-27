<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Bus\Event\DomainEvent;

abstract class AggregateRoot
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    /**
     * @return DomainEvent[]
     */
    final public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    final protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return array<mixed>
     */
    abstract public function toArray(bool $isNestedArray = false): array;
}
