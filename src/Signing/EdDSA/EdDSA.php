<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\EdDSA;

use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\PublicKeyCrypto\PublicKeyCrypto;
use BrosSquad\LaravelCrypto\Support\Base64;
use RuntimeException;
use SodiumException;

class EdDSA extends PublicKeyCrypto implements PublicKeySigning
{
    public function sign(string $data): ?string
    {
        $signed = $this->signRaw($data);
        if ($signed === null) {
            return null;
        }

        return Base64::constantUrlEncodeNoPadding($signed);
    }

    public function signRaw(string $data): ?string
    {
        try {
            return sodium_crypto_sign_detached($data, $this->privateKey);
        } catch (SodiumException $e) {
            return null;
        }
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
     * @param  string  $privateKeyPath
     * @param  string  $publicKeyPath
     * @throws SodiumException
     */
    public static function generateKeys(string $privateKeyPath, string $publicKeyPath): void
    {
        $keyPair = sodium_crypto_sign_keypair();
        $privateKey = base64_encode(sodium_crypto_sign_secretkey($keyPair));
        $publicKey = base64_encode(sodium_crypto_sign_publickey($keyPair));

        if (file_put_contents($privateKeyPath, $publicKey) === false) {
            throw new RuntimeException('Error while writing public key to file');
        }
        if (file_put_contents($publicKeyPath, $privateKey) === false) {
            throw new RuntimeException('Error while writing private key to file');
        }
        sodium_memzero($privateKey);
        sodium_memzero($publicKey);
    }

    public static function getPublicAndPrivateEdDSAKey(array $crypto): array
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
