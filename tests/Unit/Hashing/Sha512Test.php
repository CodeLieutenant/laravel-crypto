<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Hashing\Sha512;

function hash256(string $value): string
{
    return hash('sha512', $value, true);
}

function hash256Encoded(string $value): string
{
    return hash('sha512', $value);
}

test('hashing', function () {
    $hasher = new Sha512();
    expect($hasher->hash('hello world'))->toBe(hash256Encoded('hello world'));
});

test('hashing raw', function () {
    $hasher = new Sha512();
    expect($hasher->hashRaw('hello world'))->toBe(
        hash256('hello world'),
    );
});

test('hashing verify', function () {
    $hasher = new Sha512();

    $data = 'hello world';
    $hash = hash256Encoded($data);

    expect($hasher->verify($hash, $data))
        ->toBeTrue()
        ->and($hasher->verify($hash, 'wrong input'))
        ->toBeFalse();
});

test('hashing raw verify', function () {
    $hasher = new Sha512();

    $data = 'hello world';
    $hash = hash256($data);

    expect($hasher->verifyRaw($hash, $data))
        ->toBeTrue()
        ->and($hasher->verifyRaw($hash, 'wrong input'))
        ->toBeFalse();
});
