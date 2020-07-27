<?php


namespace BrosSquad\LaravelHashing\Hmac;

use BrosSquad\LaravelHashing\Hmac\Hmac;

class Hmac512 extends Hmac
{

    public function sign(string $data): ?string
    {
        return hash_hmac('sha3-512', $data, $this->key);
    }

}
