<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Traits\ConstantTimeCompare;

uses(ConstantTimeCompare::class);

test('equals near constant time', function () {
    $randomData = random_bytes(32);

    // Warm up
    for ($i = 0; $i < 10; $i++) {
        $this->equals($randomData, $randomData);
    }

    $start = hrtime(as_number: true);
    $this->equals($randomData, $randomData);
    $end = hrtime(as_number: true);

    $start2 = hrtime(as_number: true);
    $this->equals($randomData, $randomData);
    $end2 = hrtime(as_number: true);

    $diff1 = $end - $start;
    $diff2 = $end2 - $start2;

    // This is a very rough estimate, but it should be enough to see if the time is near constant
    expect($diff1 >= $diff2 ? $diff2 - $diff1 : $diff1 - $diff2)
        ->toBeGreaterThanOrEqual(-250) // 250 nanoseconds
        ->toBeLessThanOrEqual(0);
});
