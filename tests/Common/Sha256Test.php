<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelHashing\Tests\Common;


use BrosSquad\LaravelHashing\Common\Sha256;
use BrosSquad\LaravelHashing\Tests\TestCase;

class Sha256Test extends TestCase
{
    protected $sha256;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sha256 = new Sha256();
    }

    public function test_hash(): void {
        $hashedExpected = hash('sha512/256', 'Hello World');
        $hashed = $this->sha256->hash('Hello World');
        self::assertIsString($hashed);
        self::assertEquals($hashedExpected, $hashed);
    }

    public function test_hash_raw(): void {
        $hashedExpected = hash('sha512/256', 'Hello World', true);
        $hashed = $this->sha256->hashRaw('Hello World');
        self::assertIsBinary($hashed);
        self::assertEquals($hashedExpected, $hashed);
    }
}
