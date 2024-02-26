<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;

it('should encrypt/decrypt data', function (bool $serialize) {
    $encryptor = new AesGcm256Encryptor(inMemoryKeyLoader());
    $data = $serialize ? ['data'] : 'hello world';
    $encrypted = $encryptor->encrypt($data, $serialize);

    expect($encrypted)
        ->toBeString()
        ->and($encryptor->decrypt($encrypted, $serialize))
        ->toBe($data);
})->with([true, false]);

it('should encrypt/decrypt string', function () {
    $encryptor = new AesGcm256Encryptor(inMemoryKeyLoader());
    $data = 'hello world';
    $encrypted = $encryptor->encryptString($data);

    expect($encrypted)
        ->toBeString()
        ->and($encryptor->decryptString($encrypted))
        ->toBe($data);
});
