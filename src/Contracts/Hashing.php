<?php


namespace BrosSquad\LaravelHashing\Contracts;


interface Hashing
{
    public function hash(string $data): ?string;
    public function equals(string $hash1, string $hash2): bool;
}
