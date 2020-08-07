<?php


namespace BrosSquad\LaravelCrypto\Benchmarks\Encryption;


use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;
use Illuminate\Encryption\Encrypter;

class DecryptionBench
{

    /**
     * @var Encrypter
     */
    private $laravelEncrypter;

    /**
     * @var XChaCha20Poly5Encryptor
     */
    private $xchacha;

    /**
     * @var AesGcm256Encryptor
     */
    private $aes256gcm;

    /**
     * @var string
     */
    private $xChaChaData;

    /**
     * @var string
     */
    private $aes256GcmData;

    /**
     * @var string
     */
    private $laravelData;

    public function __construct()
    {
        $key = base64_decode(file_get_contents(__DIR__.'/key'));
        $this->laravelEncrypter = new Encrypter($key, 'AES-256-CBC');
        $this->xchacha = new XChaCha20Poly5Encryptor($key);
        $this->aes256gcm = new AesGcm256Encryptor($key);
        $this->xChaChaData = file_get_contents(__DIR__.'/encrypted-xchacha');
        $this->laravelData = file_get_contents(__DIR__.'/encrypted-laravel');
        $this->aes256GcmData = file_get_contents(__DIR__.'/encrypted-aes256gcm');
    }


    public function benchLaravelDecryption(): void
    {
        $this->laravelEncrypter->decryptString($this->laravelData);
    }

    public function benchXChaCha20Poly1305Decryption(): void
    {
        $this->xchacha->decryptString($this->xChaChaData);
    }

    public function benchAes256gcmDecryption(): void
    {
        $this->aes256gcm->decryptString($this->aes256GcmData);
    }
}
