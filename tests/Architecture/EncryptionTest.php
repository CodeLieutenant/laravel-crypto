<?php

declare(strict_types=1);

use CodeLieutenant\LaravelCrypto\Contracts\KeyGeneration;
use CodeLieutenant\LaravelCrypto\Encryption\AesGcm256Encryptor;
use CodeLieutenant\LaravelCrypto\Encryption\Crypto;
use CodeLieutenant\LaravelCrypto\Encryption\Encryption;
use CodeLieutenant\LaravelCrypto\Encryption\XChaCha20Poly1305Encryptor;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Encryption\StringEncrypter;

arch('encryption xchacha')
    ->expect(XChaCha20Poly1305Encryptor::class)
    ->toImplement([Encrypter::class, StringEncrypter::class, KeyGeneration::class])
    ->toBeClass()
    ->toBeFinal();

arch('encryption aesgcm256')
    ->expect(AesGcm256Encryptor::class)
    ->toImplement([Encrypter::class, StringEncrypter::class, KeyGeneration::class])
    ->toBeClass()
    ->toBeFinal();

arch('encryption enum')
    ->expect(Encryption::class)
    ->toBeStringBackedEnum();

arch('encryption crypto')
    ->expect(Crypto::class)
    ->toBeTrait();
