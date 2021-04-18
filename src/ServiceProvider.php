<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto;

use BrosSquad\LaravelCrypto\Console\GenerateCryptoKeys;
use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\Encryption\SodiumEncryptor;
use BrosSquad\LaravelCrypto\Hashing\HashingManager;
use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSA;
use BrosSquad\LaravelCrypto\Signing\SigningManager;
use RuntimeException;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac256;
use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac512;
use BrosSquad\LaravelCrypto\Hashing\Sha256;
use BrosSquad\LaravelCrypto\Hashing\Sha512;
use BrosSquad\LaravelCrypto\Hashing\Blake2b;
use BrosSquad\LaravelCrypto\Contracts\Signing;
use BrosSquad\LaravelCrypto\Contracts\Hashing;
use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;

/**
 * Class ServiceProvider
 *
 * @package BrosSquad\LaravelCrypto
 */
class ServiceProvider extends LaravelServiceProvider
{
    use LaravelKeyParser;

    protected array $hashes = [
        Hashing::class => Blake2b::class,
        'blake2b' => Blake2b::class,
        'sha256' => Sha256::class,
        'sha512' => Sha512::class,
    ];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__.'/../config/crypto.php' => config_path('crypto.php'),
                ]
            );
        }
    }

    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    GenerateCryptoKeys::class
                ]
            );
        }

        $this->mergeConfigFrom(__DIR__.'/../config/crypto.php', 'crypto');

        $this->addSignatures();
        $this->addEncrypter();
        $this->addHashing();
    }

    protected function getEncrypter($app)
    {
        $config = $app->make('config');

        $key = $this->parseKey($config->get('app.key'));
        $cipher = $config->get('app.cipher');
        switch ($cipher) {
            case SodiumEncryptor::XChaCha20Poly1305:
                return new XChaCha20Poly5Encryptor($key);
            case SodiumEncryptor::AES256GCM:
                return new AesGcm256Encryptor($key);
            default:
                return new Encrypter($key, $cipher);
        }
    }

    protected function addEncrypter(): void
    {
        $this->app->singleton('encrypter', fn($app) => $this->getEncrypter($app));

        $this->app->singleton(Encrypter::class, fn($app) => $this->getEncrypter($app));
    }

    protected function addSignatures(): void
    {
        $this->app->singleton('signing', SigningManager::class);

        $this->app->singleton(Signing::class, 'hmac256');

        $this->app->singleton(
            PublicKeySigning::class,
            fn($app) => $app->make('eddsa')
        );

        $this->app->singleton(
            'eddsa',
            fn($app) => new EdDSA(

                ...EdDSA::getPublicAndPrivateEdDSAKey($app->make('config')->get('crypto.public_key_crypto.eddsa'))
            )
        );

        $this->app->singleton(
            'hmac256',
            fn($app) => new Hmac256($this->getKey())
        );

        $this->app->singleton(
            'hmac512',
            fn($app) => new Hmac512($this->getKey())
        );

        $this->app->singleton(
            'hmacblake2b',
            fn($app) => new Hmac512($this->getKey())
        );
    }

    protected
    function addHashing(): void
    {
        $this->app->singleton(HashingManager::class);

        foreach ($this->hashes as $hashName => $bind) {
            $this->app->singleton($hashName, $bind);
        }
    }
}
