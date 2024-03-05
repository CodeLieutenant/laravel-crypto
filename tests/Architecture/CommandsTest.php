<?php

declare(strict_types=1);

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

arch('commands')
    ->expect('CodeLieutenant\LaravelCrypto\Console')
    ->toHaveAttribute(AsCommand::class)
    ->toExtend(Command::class)
    ->toHaveMethod('handle')
    ->toHaveSuffix('Command');
