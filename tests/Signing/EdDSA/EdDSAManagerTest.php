<?php


namespace BrosSquad\LaravelCrypto\Tests\Signing\EdDSA;


use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSAManager;
use BrosSquad\LaravelCrypto\Tests\TestCase;

class EdDSAManagerTest extends TestCase
{
    protected $edDsaManager;

    protected function setUp(): void
    {
        parent::setUp();
        $keyPair = sodium_crypto_sign_keypair();

        $privateKey = sodium_crypto_sign_secretkey($keyPair);
        $publicKey = sodium_crypto_sign_publickey($keyPair);

        $this->edDsaManager = new EdDSAManager($privateKey, $publicKey);

    }

    public function test_raw_signing(): void
    {
        $message = 'Hello World';

        $signature = $this->edDsaManager->signRaw($message);

        self::assertSame(SODIUM_CRYPTO_SIGN_BYTES, mb_strlen($signature, '8bit'));
    }

    public function test_invalid_signature(): void
    {
        $message = 'Hello World';

        $signature = $this->edDsaManager->sign($message);
        self::assertFalse($this->edDsaManager->verify('invalid messsage', $signature));
    }

    public function test_invalid_raw_signature(): void
    {
        $message = 'Hello World';

        $signature = $this->edDsaManager->signRaw($message);
        self::assertFalse($this->edDsaManager->verify('invalid messsage', $signature));
    }

    public function test_verify_with_wrong_number_of_bytes_in_signature(): void
    {
        self::assertFalse($this->edDsaManager->verify('Some message', str_repeat('\xFF', 5)));
    }

    public function test_verify_with_wrong_signature_input(): void
    {
        self::assertFalse($this->edDsaManager->verify('Some message', str_repeat('a', SODIUM_CRYPTO_SIGN_BYTES)));
    }

    public function test_signing_and_verification(): void
    {
        $message = 'Hello World';

        $signature = $this->edDsaManager->sign($message);

        self::assertNotNull($signature);
        self::assertIsBase64UrlNoPadding($signature);

        self::assertTrue($this->edDsaManager->verify($message, $signature));
    }
    public function test_signing_and_verification_with_raw_data(): void
    {
        $message = 'Hello World';

        $signature = $this->edDsaManager->signRaw($message);

        self::assertNotNull($signature);
        self::assertIsBinary($signature);

        self::assertTrue($this->edDsaManager->verify($message, $signature));
    }
}
