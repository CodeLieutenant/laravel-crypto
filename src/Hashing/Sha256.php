<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

use BrosSquad\LaravelCrypto\Contracts\Hashing;
use BrosSquad\LaravelCrypto\Hashing\Traits\Hash;
use BrosSquad\LaravelCrypto\Traits\ConstantTimeCompare;

final class Sha256 implements Hashing
{
    use Hash;
    use ConstantTimeCompare;

    public const ALGORITHM = 'sha512/256';

    public function hash(string $data): string
    {
        return hash(self::ALGORITHM, $data);
    }

    public function hashRaw(string $data): string
    {
        return hash(self::ALGORITHM, $data, true);
    }
}
