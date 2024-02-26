<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Tests\InMemoryAppKeyLoader;
use BrosSquad\LaravelCrypto\Tests\TestCase;
use BrosSquad\LaravelCrypto\Keys\Loader;

uses(TestCase::class)->in(__DIR__);

function inMemoryKeyLoader(): Loader
{
    return new InMemoryAppKeyLoader(config('app.key'));
}

expect()->extend('toBeBase64', function () {
    if (!preg_match('/^[-A-Za-z0-9+\/]+={0,3}$/', preg_quote($this->value, '/'))) {
        throw new RuntimeException(sprintf('Value %s is not a valid base64 string', $this->value));
    }

    return $this;
});

expect()->extend('toBeBase64NoPadding', function () {
    if (!preg_match('/^[-A-Za-z0-9+\/]+$/', preg_quote($this->value, '/'))) {
        throw new RuntimeException(sprintf('Value %s is not a valid base64 string', $this->value));
    }
    return $this;
});

expect()->extend('toBeBase64Url', function () {
    if (!preg_match('/^[-A-Za-z0-9_-]+={0,3}$/', $this->value)) {
        throw new RuntimeException(sprintf('Value %s is not a valid base64 string', $this->value));
    }

    return $this;
});

expect()->extend('toBeBase64UrlNoPadding', function () {
    if (!preg_match('/^[-A-Za-z0-9_-]+$/', $this->value)) {
        throw new RuntimeException(sprintf('Value %s is not a valid base64 string', $this->value));
    }

    return $this;
});