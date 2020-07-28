<?php


namespace BrosSquad\LaravelHashing\Tests;


use BrosSquad\LaravelHashing\Common\Blake2b;
use BrosSquad\LaravelHashing\Contracts\Hashing;

class DefaultValuesFromContainerTest extends TestCase
{
    /**
     * @test
     */
    public function should_get_default_hashing_blase2(): void
    {
        $hashing = $this->app->get(Hashing::class);
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Blake2b::class, $hashing);
    }
}
