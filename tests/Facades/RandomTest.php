<?php


namespace BrosSquad\LaravelHashing\Tests\Facades;


use BrosSquad\LaravelHashing\Tests\TestCase;
use BrosSquad\LaravelHashing\Facades\Random;

class RandomTest extends TestCase
{
    /** @test */
    public function should_give_base64_encoded_random_string(): void
    {
        $data = [
            2 =>Random::string(2),
            10 =>Random::string(10),
            15 => Random::string(15),
            20 => Random::string(20),
            50 => Random::string(50),
            64 => Random::string(64),
            80 => Random::string(80),
            100 => Random::string(100),
            500 => Random::string(500),
        ];

        foreach($data as $length => $randomString) {
            self::assertNotNull($randomString);
            self::assertIsString($randomString);
            self::assertSame(preg_match('#^[a-zA-Z0-9_-]+$#', $randomString), 1);
            self::assertEquals($length, strlen($randomString));
        }

    }
}
