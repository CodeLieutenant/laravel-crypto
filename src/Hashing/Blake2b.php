<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

use SodiumException;
use BrosSquad\LaravelCrypto\Support\Base64;

/**
 * Class Blake2b
 *
 * @package BrosSquad\LaravelCrypto\Common
 */
class Blake2b extends Hash
{
    protected string $algo = 'blake2b';

    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function hash(string $data): ?string
    {
        return Base64::constantUrlEncodeNoPadding($this->hashRaw($data));
    }

    public function hashRaw(string $data): ?string
    {
        try {
            return sodium_crypto_generichash($data, '', 64);
        } catch (SodiumException $e) {
            return null;
        }
    }
}
