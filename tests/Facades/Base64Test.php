<?php


namespace BrosSquad\LaravelHashing\Tests\Facades;


use BrosSquad\LaravelHashing\Tests\TestCase;
use BrosSquad\LaravelHashing\Facades\Base64;

class Base64Test extends TestCase
{
    /** @test */
    public function should_be_good_base64_encoded_string(): void
    {
        $data = random_bytes(100);
        self::assertEquals(base64_encode($data), Base64::encode($data));
    }

    /** @test */
    public function should_be_good_base64_decoded_buffer(): void
    {
        $data = random_bytes(100);
        $b64 = base64_encode($data);

        self::assertEquals(base64_decode($b64), Base64::decode($b64));
    }

    /** @test */
    public function should_be_good_base64_url_encoded_string(): void
    {
        $data = random_bytes(100);
        $encoded = Base64::urlEncode($data);

        self::assertSame(sodium_bin2base64($data, SODIUM_BASE64_VARIANT_URLSAFE), $encoded);
    }

    /** @test */
    public function should_be_good_base64_url_decoded_buffer(): void
    {
        $buffer = random_bytes(100);
        $b64 = sodium_bin2base64($buffer, SODIUM_BASE64_VARIANT_URLSAFE);
        $decoded = Base64::urlDecode($b64);

        self::assertEquals(sodium_base642bin($b64, SODIUM_BASE64_VARIANT_URLSAFE), $decoded);
    }

    /** @test */
    public function should_be_constant_time_base64_encode(): void
    {
        $buffer = random_bytes(100);
        $decoded = Base64::constantEncode($buffer);
        self::assertEquals(sodium_bin2base64($buffer, SODIUM_BASE64_VARIANT_ORIGINAL), $decoded);
    }

    /** @test */
    public function should_be_constant_time_base64_url_encode(): void
    {
        $buffer = random_bytes(100);
        $decoded = Base64::constantUrlEncode($buffer);
        self::assertEquals(sodium_bin2base64($buffer, SODIUM_BASE64_VARIANT_URLSAFE), $decoded);
    }

    /** @test */
    public function should_be_constant_time_base64_decode(): void
    {
        $buffer = random_bytes(100);
        $b64 = sodium_bin2base64($buffer, SODIUM_BASE64_VARIANT_ORIGINAL);
        $decoded = Base64::constantDecode($b64);

        self::assertEquals(sodium_base642bin($b64, SODIUM_BASE64_VARIANT_ORIGINAL), $decoded);
    }

    /** @test */
    public function should_be_constant_time_base64_url_decode(): void
    {
        $buffer = random_bytes(100);
        $b64 = sodium_bin2base64($buffer, SODIUM_BASE64_VARIANT_URLSAFE);
        $decoded = Base64::constantUrlDecode($b64);

        self::assertEquals(sodium_base642bin($b64, SODIUM_BASE64_VARIANT_URLSAFE), $decoded);
    }

    /** @test */
    public function base64_decoded_string_length(): void
    {
        self::assertEquals(32, Base64::encodedLengthToBytes('SFfsnwNlpFEzeKdchx+oWCH6+9+/pUmCEEVb2HvOxy8='));
    }

    /** @test */
    public function max_base64_decoded_string(): void
    {
        self::assertEquals(36, Base64::maxEncodedLengthToBytes(48));
    }

    /** @test */
    public function base64_encoded_string_length_with_padding(): void
    {
        self::assertEquals(44, Base64::encodedLength(32, true));
        self::assertEquals(68, Base64::encodedLength(50, true));
    }

    /** @test */
    public function base64_encoded_string_length_without_padding(): void
    {
        self::assertEquals(43, Base64::encodedLength(32, false));
        self::assertEquals(67, Base64::encodedLength(50, false));
        self::assertEquals(68, Base64::encodedLength(51, false));
    }

}
