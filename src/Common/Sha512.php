<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelHashing\Common;

/**
 * Class Sha512
 *
 * @package BrosSquad\LaravelHashing\Common
 */
class Sha512 extends Hash
{
    /**
     * @param  string  $data
     *
     * @return string
     */
    public function hash(string $data): string
    {
        return hash('sha3-512', $data);
    }
}
