<?php


namespace BrosSquad\LaravelHashing\Hmac;


use Illuminate\Support\Str;
use BrosSquad\LaravelHashing\Facades\Base64;
use Illuminate\Contracts\Config\Repository as Config;
use BrosSquad\LaravelHashing\Contracts\Hmac as HmacContract;

abstract class Hmac implements HmacContract
{
    /** @var string */
    protected $key;

    /** @var string */
    protected $keyBinary;

    public function __construct(Config $config) {
        $key = $config->get('app.key');

        if($key === null) {
            throw new \RuntimeException('Application key is not set');
        }

        if(($isBase64Encoded = Str::startsWith($key, 'base64:'))) {
            $key = Str::substr($key, 7);
        }

        $this->key = $key;
        $this->keyBinary = $isBase64Encoded ? Base64::decode($key) : hex2bin($key);
    }

    public function verify(string $message, string $hmac): bool
    {
        $generated = $this->sign($message);

        return hash_equals($generated, $hmac);
    }
}
