<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Facade;

arch('facades')
    ->expect('CodeLieutenant\LaravelCrypto\Facades')
    ->toBeClasses()
    ->toExtend(Facade::class);
