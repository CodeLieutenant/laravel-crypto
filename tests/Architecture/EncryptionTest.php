<?php

declare(strict_types=1);

use CodeLieutenant\LaravelCrypto\Contracts\KeyGeneration;
use CodeLieutenant\LaravelCrypto\Encryption\AesGcm256Encrypter;
use CodeLieutenant\LaravelCrypto\Encryption\XChaCha20Poly1305Encrypter;
use CodeLieutenant\LaravelCrypto\Enums\Encryption;
use CodeLieutenant\LaravelCrypto\Traits\Crypto;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Encryption\StringEncrypter;

arch('encryption')
    ->expect('CodeLieutenant\LaravelCrypto\Encryption')
    ->toImplement([Encrypter::class, StringEncrypter::class])
    ->toBeClasses()
    ->toHaveSuffix('Encrypter')
    ->toBeFinal();