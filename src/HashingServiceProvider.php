<?php


namespace BrosSquad\LaravelCrypto;


use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\ServiceProvider;
use BrosSquad\LaravelCrypto\Hmac\Hmac256;
use BrosSquad\LaravelCrypto\Hmac\Hmac512;
use BrosSquad\LaravelCrypto\Common\Sha256;
use BrosSquad\LaravelCrypto\Common\Sha512;
use BrosSquad\LaravelCrypto\Common\Blake2b;
use BrosSquad\LaravelCrypto\Contracts\Hmac;
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
        'blake2'       => Blake2b::class,
        'sha256'       => Sha256::class,
        'sha512'       => Sha512::class,
    ];

    public function register(): void
    {
        $this->app->singleton(HashingManager::class);
        $this->app->singleton(HmacManager::class);
        $this->app->singleton(Hmac::class, 'hmac256');
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

        foreach ($this->hashes as $hashName => $bind) {
            $this->app->singleton($hashName, $bind);
        }


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

    /**
     * Parse the encryption key.
     *
     * @param  array  $config
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

}
