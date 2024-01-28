<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto;

use BrosSquad\LaravelCrypto\Console\GenerateCryptoKeys;
use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\Encryption\SodiumEncryptor;
use BrosSquad\LaravelCrypto\Hashing\HashingManager;
use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSA;
use BrosSquad\LaravelCrypto\Signing\Hmac\Blake2b as HMacBlake2b;
use BrosSquad\LaravelCrypto\Signing\SigningManager;
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
use BrosSquad\LaravelCrypto\Support\Random82;
use BrosSquad\LaravelCrypto\Support\Random81;

/**
 * Class ServiceProvider
 *
 * @package BrosSquad\LaravelCrypto
 */
class ServiceProvider extends LaravelServiceProvider
{
    use LaravelKeyParser;


    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../config/crypto.php' => config_path('crypto.php'),
                ]
            );
        }
    }

    public function register(): void
    {
        if (!class_exists('BrosSquad\LaravelCrypto\Support\Random')) {
            $class = match (true) {
                PHP_VERSION_ID >= 80200 => Random82::class,
                default => Random81::class,
            };

            class_alias($class, 'BrosSquad\LaravelCrypto\Support\Random');
        }

        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    GenerateCryptoKeys::class
                ]
            );
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/crypto.php', 'crypto');

        $this->addSignatures();
        $this->addEncrypter();

        $this->app->singleton(
            EdDSA::class,
            fn($app) => new EdDSA(
                ...EdDSA::getPublicAndPrivateEdDSAKey($app->make('config')->get('crypto.public_key_crypto.eddsa'))
            )
        );
        $this->app->singleton(SigningManager::class);
        $this->app->singleton(HashingManager::class);
        $this->app->singleton(Hashing::class, Blake2b::class);
        $this->app->singleton(Signing::class, HMacBlake2b::class);
        $this->app->singleton(PublicKeySigning::class, EdDSA::class);
        $this->app->singleton(Hmac256::class, fn($app) => new Hmac256($this->getKey()));
        $this->app->singleton(Hmac512::class, fn($app) => new Hmac512($this->getKey()));
        $this->app->singleton(HMacBlake2b::class, fn($app) => new HMacBlake2b($this->getKey()));
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
    }

}
