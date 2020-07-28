<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelHashing\Hmac;

/**
 * Class Hmac512
 *
 * @package BrosSquad\LaravelHashing\Hmac
 */
class Hmac512 extends Hmac
{
    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function sign(string $data): ?string
    {
        return hash_hmac('sha3-512', $data, $this->key);
    }
}
