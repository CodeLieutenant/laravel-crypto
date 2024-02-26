<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

use BrosSquad\LaravelCrypto\Contracts\Hashing;
use BrosSquad\LaravelCrypto\Hashing\Traits\Hash;
use BrosSquad\LaravelCrypto\Support\Base64;
use BrosSquad\LaravelCrypto\Traits\ConstantTimeCompare;

final class Blake2b implements Hashing
{
    use Hash;
    use ConstantTimeCompare;

    public const ALGORITHM = 'blake2b';

    public function __construct(
        protected readonly ?string $key = null,
        protected readonly int $outputLength = 64,
    ) {
    }

    public function hash(string $data): string
    {
        return Base64::urlEncodeNoPadding($this->hashRaw($data));
    }

    public function hashRaw(string $data): string
    {
        return sodium_crypto_generichash($data, $this->key ?? '', $this->outputLength);
    }
}
