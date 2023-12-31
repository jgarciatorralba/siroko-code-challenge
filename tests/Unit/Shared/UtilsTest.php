<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared;

use App\Shared\Utils;
use DateTime;
use DateTimeInterface;
use Exception;

describe('dateToString', function () {
    it('should convert a DateTime object to string', function () {
        $date = new DateTime();
        $dateToString = Utils::dateToString($date);

        expect($dateToString)
            ->toBeString()
            ->toMatch('/\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d|3[0-1])T[0-2]\d:[0-5]\d:[0-5]\d[+-][0-2]\d:[0-5]\d/');
    });
});

describe('stringToDate', function () {
    it('should convert a valid string to a DateTime object', function () {
        $string = '2023-08-01';
        $date = new DateTime($string);

        expect($date)
            ->toBeInstanceOf(DateTimeInterface::class);
    });

    it('should throw an exception when using an invalid string', function () {
        $string = 'invalid-string';

        Utils::stringToDate($string);
    })->throws(Exception::class);
});

describe('extractClassName', function () {
    it('should extract the class name from a class', function () {
        $className = Utils::extractClassName(Utils::class);
        expect($className)->toBe('Utils');
    });
});

describe('toSnakeCase', function () {
    it('converts camelCase to snake_case', function () {
        $snakeCase = Utils::toSnakeCase('camelCaseString');
        expect($snakeCase)->toBe('camel_case_string');
    });

    it('converts PascalCase to snake_case', function () {
        $snakeCase = Utils::toSnakeCase('PascalCaseString');
        expect($snakeCase)->toBe('pascal_case_string');
    });
});
