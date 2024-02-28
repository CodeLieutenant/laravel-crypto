<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;

use CodeLieutenant\LaravelCrypto\Support\Random;
use Illuminate\Contracts\Config\Repository;

class Blake2bHashingKey extends AppKey
{
    public const CONFIG_KEY_PATH = 'crypto.signing.keys.hmac';
}