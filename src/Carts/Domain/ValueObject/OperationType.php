<?php

declare(strict_types=1);

namespace App\Carts\Domain\ValueObject;

enum OperationType: string
{
    case ADD = 'add';
    case UPDATE = 'update';
    case REMOVE = 'remove';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
