<?php


namespace BrosSquad\LaravelHashing\Common;


use SodiumException;

/**
 * Class Blake2b
 *
 * @package BrosSquad\LaravelHashing\Common
 */
class Blake2b extends Hash
{

    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function hash(string $data): ?string
    {
        try {
            return sodium_bin2base64(
                sodium_crypto_generichash($data, null, 64),
                SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING
            );
        } catch (SodiumException $e) {
            return null;
        }
    }
}
