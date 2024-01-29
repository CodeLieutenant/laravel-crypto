<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

use SodiumException;
use BrosSquad\LaravelCrypto\Support\Base64;

class Blake2b extends Hash
{
    public const ALGORITHM = 'blake2b';
    public const HASH_SIZE = 64;

    public function __construct(protected ?string $key = null)
    {
    }

    public function hash(string $data): string
    {
        return Base64::urlEncodeNoPadding($this->hashRaw($data));
    }

    public function hashRaw(string $data): string
    {
        return sodium_crypto_generichash($data, $this->key ?? '', self::HASH_SIZE);
    }
}
