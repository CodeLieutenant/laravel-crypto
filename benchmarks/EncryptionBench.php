<?php

namespace BrosSquad\LaravelCrypto\Benchmarks\Encryption;

use BrosSquad\LaravelCrypto\Encoder\IgbinaryEncoder;
use BrosSquad\LaravelCrypto\Encoder\JsonEncoder;
use BrosSquad\LaravelCrypto\Encoder\PhpEncoder;
use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;
use BrosSquad\LaravelCrypto\Support\Random;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Js;
use Monolog\Logger;

class EncryptionBench
{
    private Encrypter $laravelAES256CBC;
    private Encrypter $laravelAES256GCM;
    private Encrypter $laravelAES128CBC;
    private Encrypter $laravelAES128GCM;
    private XChaCha20Poly5Encryptor $xchachaPhpEncoder;
    private XChaCha20Poly5Encryptor $xchachaJsonEncoder;
    private XChaCha20Poly5Encryptor $xchachaIgbinaryEncoder;
    private AesGcm256Encryptor $aes256gcm;

    private readonly array $data;

    public function __construct()
    {
        $logger = new Logger('benchmark');
        $phpEncoder = new PhpEncoder();
        $jsonEncoder = new JsonEncoder();
        $igbinaryEncoder = new IgbinaryEncoder();

        $this->laravelAES256CBC = new Encrypter(Random::bytes(32), 'AES-256-CBC');
        $this->laravelAES256GCM = new Encrypter(Random::bytes(32), 'AES-256-GCM');
        $this->laravelAES128GCM = new Encrypter(Random::bytes(32), 'AES-128-GCM');
        $this->laravelAES128CBC = new Encrypter(Random::bytes(32), 'AES-128-CBC');

        $this->xchachaPhpEncoder = new XChaCha20Poly5Encryptor(new KeyLoader(Random::bytes(32)), $logger, $phpEncoder);
        $this->xchachaJsonEncoder = new XChaCha20Poly5Encryptor(
            new KeyLoader(Random::bytes(32)), $logger, $jsonEncoder
        );
        $this->xchachaIgbinaryEncoder = new XChaCha20Poly5Encryptor(
            new KeyLoader(Random::bytes(32)),
            $logger,
            $igbinaryEncoder
        );

        $this->aes256gcm = new AesGcm256Encryptor(new KeyLoader(Random::bytes(32)), $logger, $phpEncoder);

        $this->data = [
            Random::bytes(32), // 32B
            Random::bytes(128), // 128B
            Random::bytes(256), // 256B
            Random::bytes(1024), // 1KB
            Random::bytes(32 * 1024), // 32KB
            Random::bytes(1024 * 1024), // 1MB
        ];
    }

//    public function benchLaravelEncryption(): void
//    {
//        $this->laravelAES256CBC->encryptString($this->data);
//    }
//
//    public function benchXChaCha20Poly1305(): void
//    {
//        $this->xchacha->encryptString($this->data);
//    }
//
//    public function benchAes256gcm(): void
//    {
//        $this->aes256gcm->encryptString($this->data);
//    }
}
