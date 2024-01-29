<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto;

use BrosSquad\LaravelCrypto\Console\GenerateCryptoKeysCommand;
use BrosSquad\LaravelCrypto\Contracts\Hashing;
use BrosSquad\LaravelCrypto\Contracts\Signing;
use BrosSquad\LaravelCrypto\Encoder\IgbinaryEncoder;
use BrosSquad\LaravelCrypto\Encoder\JsonEncoder;
use BrosSquad\LaravelCrypto\Encoder\MessagePackEncoder;
use BrosSquad\LaravelCrypto\Encoder\PhpEncoder;
use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\Encryption;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;
use BrosSquad\LaravelCrypto\Hashing\Blake2b;
use BrosSquad\LaravelCrypto\Hashing\HashingManager;
use BrosSquad\LaravelCrypto\Hashing\Sha256;
use BrosSquad\LaravelCrypto\Hashing\Sha512;
use BrosSquad\LaravelCrypto\Keys\AppKeyLoader;
use BrosSquad\LaravelCrypto\Keys\EdDSASignerKeyLoader;
use BrosSquad\LaravelCrypto\Keys\Loader;
use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSA;
use BrosSquad\LaravelCrypto\Signing\Hmac\HmacSha256;
use BrosSquad\LaravelCrypto\Signing\Hmac\HmacSha512;
use BrosSquad\LaravelCrypto\Signing\SigningManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Encryption\Encrypter as LaravelConcreteEncrypter;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Encryption\MissingAppKeyException;

class ServiceProvider extends EncryptionServiceProvider
{

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->getConfigPath() => config_path('crypto.php')]);
        }
    }

    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([GenerateCryptoKeysCommand::class]);
        }

        $this->mergeConfigFrom($this->getConfigPath(), 'crypto');

        $this->registerEncoder();
        $this->registerKeyLoaders();
        $this->registerSigners();
        $this->registerHashers();
        parent::register();
    }

    protected function registerEncoder(): void
    {
        $encoders = [
            PhpEncoder::class,
            JsonEncoder::class,
            MessagePackEncoder::class,
            IgbinaryEncoder::class,
        ];

        foreach ($encoders as $encoder) {
            $this->app->singleton($encoder, function (Application $app) use ($encoder) {
                $config = $app->make(Repository::class)->get('crypto.encoder.config.' . $encoder);
                return new $encoder(...$config);
            });
        }

        $this->app->singleton(
            Encoder\Encoder::class,
            $this->app->make(Repository::class)->get('crypto.encoder.driver')
        );
    }

    protected function registerKeyLoaders(): void
    {
        $this->app->singleton(AppKeyLoader::class);
        $this->app->singleton(EdDSASignerKeyLoader::class, static function (Application $app) {
            $keyPath = $app->make(Repository::class)->get('crypto.signing.keys.eddsa');

            if ($keyPath === null) {
                throw new MissingAppKeyException('EdDSA Private key path is not set');
            }

            return new EdDSASignerKeyLoader($keyPath, $app->make('log'));
        });
    }

    protected function registerSigners(): void
    {
        $this->app->singleton(SigningManager::class);

        $this->app->when(EdDSA::class)
            ->needs(Loader::class)
            ->give(EdDSASignerKeyLoader::class);
        $hmacSigners = [
            \BrosSquad\LaravelCrypto\Signing\Hmac\Blake2b::class,
            HmacSha256::class,
            HmacSha512::class,
        ];

        foreach ($hmacSigners as $signer) {
            $this->app->singleton($signer);
            $this->app->when($signer)
                ->needs(Loader::class)
                ->give(AppKeyLoader::class);
        }

        $this->app->register(Signing::class, static function (Application $app) {
            return $app->make($app->make(Repository::class)->get('crypto.signing.driver'));
        });
    }

    protected function registerHashers(): void
    {
        $hashers = [
            Blake2b::class,
            Sha256::class,
            Sha512::class,
        ];

        $this->app->singleton(HashingManager::class);

        foreach ($hashers as $hasher) {
            $this->app->register($hasher, static function (Application $app) use ($hasher) {
                $params = $app->make(Repository::class)->get('crypto.hashing.config.' . $hasher::ALGHORITH);

                return new $hasher(...$params);
            });
        }

        $this->app->register(Hashing::class, static function (Application $app) {
            return $app->make($app->make(Repository::class)->get('crypto.hashing.driver'));
        });
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/crypto.php';
    }

    protected function registerEncrypter(): void
    {
        foreach ([AesGcm256Encryptor::class, XChaCha20Poly5Encryptor::class] as $encryptor) {
            $this->app->singleton($encryptor, $encryptor);
            $this->app->when($encryptor)
                ->needs(Loader::class)
                ->give(AppKeyLoader::class);
        }

        $func = static function (Application $app) {
            $cipher = $app->make('config')->get('app.cipher');
            $keyLoader = $app->make(AppKeyLoader::class);

            $enc = Encryption::tryFrom($cipher);

            if ($enc === null) {
                return new LaravelConcreteEncrypter($keyLoader->getKey(), $cipher);
            }

            return match ($enc) {
                Encryption::SodiumAES256GCM => $app->make(AesGcm256Encryptor::class),
                Encryption::SodiumXChaCha20Poly1305 => $app->make(XChaCha20Poly5Encryptor::class),
            };
        };

        $this->app->singleton(Encrypter::class, $func);
        $this->app->singleton('encrypter', $func);
    }
}