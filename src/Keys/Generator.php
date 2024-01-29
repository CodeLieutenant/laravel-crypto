<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

interface Generator
{
    public function generate(): void;
}