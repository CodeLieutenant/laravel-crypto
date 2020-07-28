<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelHashing\Common;

/**
 * Class Sha256
 *
 * @package BrosSquad\LaravelHashing\Common
 */
class Sha256 extends Hash
{
    /**
     * @param  string  $data
     *
     * @return string
     */
    public function hash(string $data): string
    {
        return hash('sha512/256', $data);
    }
}
