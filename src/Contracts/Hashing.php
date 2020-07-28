<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelHashing\Contracts;

/**
 * Interface Hashing
 *
 * @package BrosSquad\LaravelHashing\Contracts
 */
interface Hashing
{
    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function hash(string $data): ?string;

    /**
     * @param  string  $hash1
     * @param  string  $hash2
     *
     * @return bool
     */
    public function equals(string $hash1, string $hash2): bool;
}
