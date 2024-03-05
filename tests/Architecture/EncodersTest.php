<?php

declare(strict_types=1);

use CodeLieutenant\LaravelCrypto\Contracts\Encoder;

arch('encoders')
    ->expect('CodeLieutenant\LaravelCrypto\Encoders')
    ->toBeClasses()
    ->toHaveSuffix('Encoder')
    ->toImplement(Encoder::class);
