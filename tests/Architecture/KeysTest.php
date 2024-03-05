<?php

declare(strict_types=1);

use CodeLieutenant\LaravelCrypto\Contracts\KeyLoader;

arch('keys')
    ->expect('CodeLieutenant\LaravelCrypto\Keys')
    ->toBeClasses()
    ->toImplement(KeyLoader::class)
    ->toHaveSuffix('Key');
