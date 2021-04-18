<?php

namespace BrosSquad\LaravelCrypto\Benchmarks\Encryption;

use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;
use Illuminate\Encryption\Encrypter;

class EncryptionBench
{
    private Encrypter $laravelEncrypter;
    private XChaCha20Poly5Encryptor $xchacha;
    private AesGcm256Encryptor $aes256gcm;
    private string $data;

    public function __construct()
    {
        $key = random_bytes(32);
        $this->laravelEncrypter = new Encrypter($key, 'AES-256-CBC');
        $this->xchacha = new XChaCha20Poly5Encryptor($key);
        $this->aes256gcm = new AesGcm256Encryptor($key);
        $this->data = file_get_contents(__DIR__.'/data');
    }

    public function benchLaravelEncryption(): void
    {
        $this->laravelEncrypter->encryptString($this->data);
    }

    public function benchXChaCha20Poly1305(): void
    {
        $this->xchacha->encryptString($this->data);
    }

    public function benchAes256gcm(): void
    {
        $this->aes256gcm->encryptString($this->data);
    }
}
