<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared;

use App\Shared\Utils;
use DateTime;

describe('dateToString', function () {
    it('should convert a DateTime object to string', function () {
        $date = new DateTime();
        $dateToString = Utils::dateToString($date);

        expect($dateToString)
            ->toBeString()
            ->toMatch('/\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d|3[0-1])T[0-2]\d:[0-5]\d:[0-5]\d[+-][0-2]\d:[0-5]\d/');
    });
});
