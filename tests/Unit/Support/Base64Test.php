<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Support\Base64;

it('encodes binary data', function () {
    $data = random_bytes(32);

    $encoded = Base64::encode($data);

    expect($encoded)->toBe(base64_encode($data));
});
