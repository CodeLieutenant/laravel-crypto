<?php


namespace BrosSquad\LaravelCrypto\Hmac;


use SodiumException;
use BrosSquad\LaravelCrypto\Contracts\Hmac as HmacContract;

abstract class Hmac implements HmacContract
{

    /** @var string */
    protected $key;

    /**
     * Hmac constructor.
     *
     * @param  string  $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @param  string  $message
     * @param  string  $hmac
     *
     * @return bool
     */
    public function verify(string $message, string $hmac): bool
    {
        $generated = $this->sign($message);

        return hash_equals($generated, $hmac);
    }

    public function __destruct()
    {
        try {
            sodium_memzero($this->key);
            sodium_memzero($this->key);
        } catch (SodiumException $e) {
        }
    }
}
