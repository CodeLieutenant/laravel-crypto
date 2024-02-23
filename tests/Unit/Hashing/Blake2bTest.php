<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Hashing\Blake2b;

test('blake2b hashing', function () {
    $outputLength = 32;
    $hasher = new Blake2b(outputLength: $outputLength);
    expect($hasher->hash('hello world'))->toBe(
        str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode(sodium_crypto_generichash('hello world', '', $outputLength)),
        )
    );
});

test('blake2b hashing raw', function () {
    $outputLength = 32;
    $hasher = new Blake2b(outputLength: $outputLength);
    expect($hasher->hashRaw('hello world'))->toBe(
        sodium_crypto_generichash('hello world', '', $outputLength),
    );
});

test('blake2b hashing with key', function () {
    $key = random_bytes(32);
    $outputLength = 32;
    $hasher = new Blake2b($key, $outputLength);


    expect($hasher->hash('hello world'))->toBe(
        str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode(sodium_crypto_generichash('hello world', $key, $outputLength)),
        )
    );
});

test('blake2b hashing raw with key', function () {
    $key = random_bytes(32);
    $outputLength = 32;
    $hasher = new Blake2b($key, $outputLength);


    expect($hasher->hashRaw('hello world'))->toBe(
        sodium_crypto_generichash('hello world', $key, $outputLength),
    );
});
