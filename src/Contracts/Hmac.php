<?php


namespace BrosSquad\LaravelHashing\Contracts;


interface Hmac
{
    public function sign(string $data): ?string;
    public function verify(string $message, string $hmac): bool;
}
