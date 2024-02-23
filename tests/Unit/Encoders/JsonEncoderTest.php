<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Encoder\JsonEncoder;

test('encode', function () {
    $encoder = new JsonEncoder();

    $data = ['name' => 'John Doe', 'age' => 25];

    $encoded = $encoder->encode($data);

    expect($encoded)->toBe('{"name":"John Doe","age":25}');
});


test('decode as array', function () {
    $encoder = new JsonEncoder(asArray: true);

    $data = '{"name":"John Doe","age":25}';

    $decoded = $encoder->decode($data);

    expect($decoded)->toBe(['name' => 'John Doe', 'age' => 25]);
});

test('decode as object', function () {
    $encoder = new JsonEncoder(asArray: false);

    $data = '{"name":"John Doe","age":25}';

    $decoded = $encoder->decode($data);

    $class = new stdClass();
    $class->name = 'John Doe';
    $class->age = 25;

    expect($decoded)->toBeObject()
        ->toEqual($class);
});