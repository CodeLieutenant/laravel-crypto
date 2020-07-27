<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelHashing\Common;


class Sha256 extends Hash
{
    public function hash(string $data): string
    {
        return hash('sha512/256', $data);
    }
}
