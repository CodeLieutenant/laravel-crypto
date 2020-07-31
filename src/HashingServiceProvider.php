<?php


namespace BrosSquad\LaravelCrypto;


use BrosSquad\LaravelCrypto\Console\GenerateCryptoKeys;
use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSAManager;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\ServiceProvider;
use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac256;
use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac512;
use BrosSquad\LaravelCrypto\Common\Sha256;
use BrosSquad\LaravelCrypto\Common\Sha512;
use BrosSquad\LaravelCrypto\Common\Blake2b;
use BrosSquad\LaravelCrypto\Contracts\Signing;
use BrosSquad\LaravelCrypto\Contracts\Hashing;
use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;

/**
 * Class HashingServiceProvider
 *
 * @package BrosSquad\LaravelCrypto
 */
class HashingServiceProvider extends ServiceProvider
{
    protected $hashes = [
        Hashing::class => Blake2b::class,
        'blake2' => Blake2b::class,
        'sha256' => Sha256::class,
        'sha512' => Sha512::class,
    ];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/crypto.php' => config_path('crypto.php'),
            ]);
        }
    }

    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCryptoKeys::class
            ]);
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/crypto.php', 'crypto');
        $this->app->singleton(HashingManager::class);
        $this->app->singleton(HmacManager::class);

        $this->addSignatures();
        $this->addEncrypter();
        $this->addHashing();
    }

    protected function addEncrypter(): void
    {
        $this->app->singleton(
            'encrypter',
            function ($app) {
                $config = $app->make('config')->get('app');

                $key = $this->parseKey($config);
                $cipher = $config['cipher'];
                switch ($cipher) {
                    case 'AES-128-CBC':
                    case 'AES-256-CBC':
                        return new Encrypter($key, $cipher);
                    case 'XChaCha20Poly1305':
                        return new XChaCha20Poly5Encryptor($cipher);
                    case 'AES-256-GCM':
                        return new AesGcm256Encryptor($key);
                    default:
                        throw new RuntimeException(
                            'Choose the encryption algorithms: XChaCha20Poly1305, AES-128-CBC, AES-256-CBC, AES-256-GCM'
                        );
                }
            }
        );
    }

    protected function addSignatures(): void
    {
        $this->app->singleton(Signing::class, 'hmac256');
        $this->app->singleton(PublicKeySigning::class, function ($app) {
            $crypto = $app->make('config')->get('crypto');
            return new EdDSAManager(...$this->getPublicAndPrivateEdDSAKey($crypto));
        });
        $this->app->singleton(
            'hmac256',
            function ($app) {
                return new Hmac256($this->parseKey($app->make('config')->get('app')));
            }
        );
        $this->app->singleton(
            'hmac512',
            function ($app) {
                $config = $app->make('config')->get('app');
                return new Hmac512($this->parseKey($config));
            }
        );


    }

    protected function addHashing(): void
    {
        foreach ($this->hashes as $hashName => $bind) {
            $this->app->singleton($hashName, $bind);
        }
    }

    /**
     * Parse the encryption key.
     *
     * @param array $config
     *
     * @return string
     */
    protected function parseKey(array $config): string
    {
        if (empty($config['key'])) {
            throw new RuntimeException(
                'No application encryption key has been specified.'
            );
        }

        if (Str::startsWith($key = $config['key'], $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }


    protected function getPublicAndPrivateEdDSAKey(array $crypto): array
    {
        if (!$crypto['private_key']) {
            throw new RuntimeException('EdDSA Private key path is not set');
        }

        if (!$crypto['public_key']) {
            throw new RuntimeException('EdDSA Public key path is not set');
        }

        return [
            base64_decode(file_get_contents($crypto['private_key'])),
            base64_decode(file_get_contents($crypto['public_key'])),
        ];
    }
}
