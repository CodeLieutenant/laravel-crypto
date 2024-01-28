<?php
declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests\Support;

use BrosSquad\LaravelCrypto\Tests\TestCase;
use BrosSquad\LaravelCrypto\Support\Random81;

class RandomTest extends TestCase
{
    /** @test */
    public function should_give_base64_encoded_random_string(): void
    {
        $data = [
            2 =>Random81::string(2),
            10 =>Random81::string(10),
            15 => Random81::string(15),
            20 => Random81::string(20),
            50 => Random81::string(50),
            64 => Random81::string(64),
            80 => Random81::string(80),
            100 => Random81::string(100),
            500 => Random81::string(500),
        ];

        foreach($data as $length => $randomString) {
            self::assertNotNull($randomString);
            self::assertIsString($randomString);
            self::assertIsBase64UrlNoPadding($randomString);
            self::assertEquals($length, mb_strlen($randomString, '8bit'));
        }

    }
}
