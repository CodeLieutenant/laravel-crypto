<?php


namespace BrosSquad\LaravelHashing\Common;


use SodiumException;

class Blake2b extends Hash
{

    public function hash(string $data): ?string
    {
        try {
            return sodium_bin2base64(sodium_crypto_generichash($data, '', 64), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
        } catch (SodiumException $e) {
            return null;
        }
    }
}
