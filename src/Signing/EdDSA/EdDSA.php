<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\EdDSA;

use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\Exceptions\KeyPathNotFound;
use BrosSquad\LaravelCrypto\PublicKeyCrypto\PublicKeyCrypto;
use BrosSquad\LaravelCrypto\Support\Base64;
use RuntimeException;
use SodiumException;
use SplFileObject;

class EdDSA extends PublicKeyCrypto implements PublicKeySigning
{
    public function sign(string $data): ?string
    {
        $signed = $this->signRaw($data);
        if ($signed === null) {
            return null;
        }

        return Base64::urlEncodeNoPadding($signed);
    }

    public function signRaw(string $data): ?string
    {
        return sodium_crypto_sign_detached($data, $this->privateKey);
    }

    public function verify(string $message, string $hmac): bool
    {
        try {
            // If signature can be printed,
            // then it should be encoded as base64url with no padding
            if (ctype_print($hmac)) {
                $decodedSignature = Base64::constantUrlDecodeNoPadding($hmac);
            } else {
                // otherwise we treat it as binary
                $decodedSignature = $hmac;
            }

            if ($decodedSignature === null) {
                return false;
            }

            return sodium_crypto_sign_verify_detached($decodedSignature, $message, $this->publicKey);
        } catch (SodiumException $e) {
            return false;
        }
    }

    /**
     * @param string $outputPath
     * @throws SodiumException
     */
    public static function generateKeys(string $outputPath): void
    {
        $keyPair = sodium_crypto_sign_keypair();
        $privateKey = sodium_crypto_sign_secretkey($keyPair);
        $publicKey = sodium_crypto_sign_publickey($keyPair);

        if (@file_put_contents($outputPath, $publicKey . $privateKey) === false) {
            throw new RuntimeException('Error while writing public key to file');
        }

        sodium_memzero($privateKey);
        sodium_memzero($publicKey);
        sodium_memzero($keyPair);
    }

    public static function getPublicAndPrivateEdDSAKey(array $crypto): array
    {
        if (!$crypto['key']) {
            throw new KeyPathNotFound('EdDSA Private key path is not set');
        }

        $file = new SplFileObject($crypto['key'], 'r');
        if ($file->flock(LOCK_SH) === false) {
            throw new RuntimeException('Error while locking file (shared/reading)');
        }

        $keys = $file->fread(SODIUM_CRYPTO_SIGN_KEYPAIRBYTES);
        if ($keys === false) {
            throw new RuntimeException('Error while reading key');
        }

        return [
            substr($keys, 0, SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES),
            substr($keys, SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES, SODIUM_CRYPTO_SIGN_SECRETKEYBYTES),
        ];
    }
}
