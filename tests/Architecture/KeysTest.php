<?php

declare(strict_types=1);

use CodeLieutenant\LaravelCrypto\Contracts\KeyGenerator;
use CodeLieutenant\LaravelCrypto\Contracts\KeyLoader;

arch('keys loaders')
    ->expect('CodeLieutenant\LaravelCrypto\Keys\Loaders')
    ->toBeClasses()
    ->toOnlyImplement(KeyLoader::class)
    ->toHaveSuffix('KeyLoader');

arch('key generators')
    ->expect('CodeLieutenant\LaravelCrypto\Keys\Generators')
    ->toBeClasses()
    ->toImplement(KeyGenerator::class)
    ->toHaveSuffix('KeyGenerator');
