<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;

class HmacKey extends AppKey
{
    public const CONFIG_KEY_PATH = 'crypto.signing.keys.hmac';
}