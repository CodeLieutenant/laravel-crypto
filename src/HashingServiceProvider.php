<?php


namespace BrosSquad\LaravelCrypto;


use Illuminate\Support\ServiceProvider;
use BrosSquad\LaravelCrypto\Hmac\Hmac256;
use BrosSquad\LaravelCrypto\Hmac\Hmac512;
use BrosSquad\LaravelCrypto\Common\Sha256;
use BrosSquad\LaravelCrypto\Common\Sha512;
use BrosSquad\LaravelCrypto\Common\Blake2b;
use BrosSquad\LaravelCrypto\Contracts\Hmac;
use BrosSquad\LaravelCrypto\Contracts\Hashing;

/**
 * Class HashingServiceProvider
 *
 * @package BrosSquad\LaravelCrypto
 */
class HashingServiceProvider extends ServiceProvider
{
    protected $hashes = [
        Hashing::class => Blake2b::class,
        'blake2'       => Blake2b::class,
        'sha256'       => Sha256::class,
        'sha512'       => Sha512::class,
    ];

    protected $macs = [
        Hmac::class => Hmac256::class,
        'hmac256'   => Hmac256::class,
        'hmac512'   => Hmac512::class,
    ];

    public function register(): void
    {
        $this->app->singleton(HashingManager::class);
        $this->app->singleton(HmacManager::class);

        foreach ($this->hashes as $hashName => $bind) {
            $this->app->singleton($hashName, $bind);
        }

        foreach ($this->macs as $hashName => $bind) {
            $this->app->singleton($hashName, $bind);
        }
    }

}
