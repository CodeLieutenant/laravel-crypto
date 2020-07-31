<?php


namespace BrosSquad\LaravelCrypto\Signing\EdDSA;


use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\Facades\Base64;
use RuntimeException;
use SodiumException;

class EdDSAManager implements PublicKeySigning
{
    /**
     * @var string
     */
    private $privateKey;
    /**
     * @var string
     */
    private $publicKey;

    public function __construct(string $privateKey, string $publicKey)
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

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
            return sodium_crypto_sign_verify_detached($decodedSignature, $message, $this->publicKey);
        } catch (SodiumException $e) {
            return false;
        }
    }

    /**
     * @param string $privateKeyPath
     * @param string $publicKeyPath
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

    public function __destruct()
    {
        sodium_memzero($this->privateKey);
        sodium_memzero($this->publicKey);
    }
}
