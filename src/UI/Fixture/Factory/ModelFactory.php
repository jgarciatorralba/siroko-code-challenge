<?php

declare(strict_types=1);

namespace App\UI\Fixture\Factory;

use Error;
use Faker;
use Iterator;
use RuntimeException;
use Throwable;
use TypeError;

abstract class ModelFactory
{
    private const DEFAULT_MANY_NUMBER = 5;

    final public function __construct(
        private readonly Faker\Generator $faker
    ) {
    }

    protected function faker(): Faker\Generator
    {
        return $this->faker;
    }

    final public static function new(): static
    {
        $faker = Faker\Factory::create();
        return new static($faker);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    final public function createOne(array $attributes = []): object
    {
        $attributes = array_merge($this->getDefaultAttributes(), $attributes);
        $modelClass = $this->getModelClass();

        try {
            return new $modelClass(...$attributes);
        } catch (Throwable | Error $exception) {
            throw new RuntimeException(
                sprintf(
                    "Error creating model object '%s': %s",
                    $modelClass,
                    $exception->getMessage()
                ),
                0,
                $exception
            );
        }
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<object>
     */
    final public function createMany(
        array $attributes = [],
        int $number = self::DEFAULT_MANY_NUMBER
    ): array {
        $collection = [];

        for ($i = 0; $i < $number; $i++) {
            $collection[] = $this->createOne($attributes);
        }

        return $collection;
    }

    /**
     * @template TKey
     * @template TValue
     * @param Iterator<TKey, TValue> $sequence
     * @return array<object>
     */
    final public function createSequence(callable|iterable $sequence): array
    {
        if (is_callable($sequence)) {
            $sequence = $sequence();
        }

        if (!is_iterable($sequence)) {
            throw new TypeError(sprintf(
                "Parameter '$sequence' is expected to be an iterable, got '%s'",
                get_debug_type($sequence)
            ));
        }

        $collection = [];
        foreach ($sequence as $key => $attributes) {
            $collection[$key] = $this->createOne($attributes);
        }

        return $collection;
    }

    abstract protected function getModelClass(): string;

    /**
     * @return array<string, mixed>
     */
    abstract protected function getDefaultAttributes(): array;
}
