<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly1305Encryptor;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;
use BrosSquad\LaravelCrypto\Encryption\Encryption;

test('encrypter resolver', function (string $cipher, string $instance) {
    Config::set('app.cipher', $cipher);

    $encrypter = $this->app->make('encrypter');

    expect($encrypter)->toBeInstanceOf($instance);
})->with([
    ['AES-256-GCM', Encrypter::class],
    ['AES-256-CBC', Encrypter::class],
    [Encryption::SodiumAES256GCM->value, AesGcm256Encryptor::class],
    [Encryption::SodiumXChaCha20Poly1305->value, XChaCha20Poly1305Encryptor::class],
]);
