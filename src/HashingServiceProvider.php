<?php


namespace BrosSquad\LaravelHashing;


use Illuminate\Support\ServiceProvider;
use BrosSquad\LaravelHashing\Hmac\Hmac256;
use BrosSquad\LaravelHashing\Hmac\Hmac512;
use BrosSquad\LaravelHashing\Common\Sha256;
use BrosSquad\LaravelHashing\Common\Sha512;
use BrosSquad\LaravelHashing\Common\Blake2b;
use BrosSquad\LaravelHashing\Contracts\Hmac;
use BrosSquad\LaravelHashing\Contracts\Hashing;

/**
 * Class HashingServiceProvider
 *
 * @package BrosSquad\LaravelHashing
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
