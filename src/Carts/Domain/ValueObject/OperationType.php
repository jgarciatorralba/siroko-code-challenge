<?php

declare(strict_types=1);

namespace App\Carts\Domain\ValueObject;

final class OperationType
{
    final public const OPERATION_ADD = 'add';
    final public const OPERATION_UPDATE = 'update';
    final public const OPERATION_REMOVE = 'remove';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return [self::OPERATION_ADD, self::OPERATION_UPDATE, self::OPERATION_REMOVE];
    }
}
