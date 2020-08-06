<?php


namespace BrosSquad\LaravelCrypto\Encryption;


use Exception;
use BrosSquad\LaravelCrypto\Facades\Base64;
use Illuminate\Contracts\Encryption\Encrypter;
use BrosSquad\LaravelCrypto\Contracts\KeyGeneration;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\DecryptException;
use BrosSquad\LaravelCrypto\Contracts\StringEncryptor;

abstract class SodiumEncryptor implements Encrypter, KeyGeneration, StringEncryptor
{
    protected $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function encryptString(string $value): string
    {
        return $this->encrypt($value, false);
    }

    public function decryptString(string $payload): string
    {
        return $this->decrypt($payload, false);
    }

    protected function encryptRaw($message, bool $serialize, int $nonceLength, callable $encryptionFunction): string
    {
        try {
            // Generate random nonce of 24bytes in length
            $nonce = random_bytes($nonceLength);

            // We use json encoding instead of php serialization. it avoids the problem
            // with many languages. (If we used php serialization, only php could deserialize it
            // or implementation of php deserialization must be made in other program)
            if ($serialize) {
                $message = json_encode($message, JSON_THROW_ON_ERROR);
            }

            $encrypted = $encryptionFunction($message, $nonce, $nonce, $this->key);
            return Base64::constantUrlEncodeNoPadding($nonce.$encrypted);
        } catch (Exception $e) {
            throw new EncryptException('Value cannot be encrypted');
        }
    }

    protected function decryptRaw(
        string $payload,
        bool $unserialize,
        int $nonceLength,
        callable $encryptionFunction
    ) {
        $decoded = Base64::constantUrlDecodeNoPadding($payload);
        $nonce = mb_substr($decoded, 0, $nonceLength, '8bit');
        $cipherText = mb_substr($decoded, $nonceLength, null, '8bit');
        try {
            $decrypted = $encryptionFunction($cipherText, $nonce, $nonce, $this->key);

            if ($unserialize) {
                return json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);
            }

            return $decrypted;
        } catch (Exception $e) {
            throw new DecryptException('Payload cannot be decrypted');
        }
    }


    public function __destruct()
    {
        sodium_memzero($this->key);
    }
}
