<?php


namespace BrosSquad\LaravelHashing\Common;


class Sha512 extends Hash
{
    public function hash(string $data): string
    {
        return hash('sha3-512', $data);
    }
}
