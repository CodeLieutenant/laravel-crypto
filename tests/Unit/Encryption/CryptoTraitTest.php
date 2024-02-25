<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Encryption\Crypto;

class TestTraitImpl
{
    use Crypto;

    public static function nonceSize(): int
    {
        return 16;
    }
}

test('generate nonce -> without previous', function () {
    $testCrypto = new TestTraitImpl();
    $nonce = $testCrypto->generateNonce();
    $nonce2 = $testCrypto->generateNonce();

    expect($nonce)->toBeString()
        ->and(strlen($nonce))
        ->toBe(16)
        ->and($nonce2)
        ->toBeString()
        ->and(strlen($nonce2))
        ->toBe(16)
        ->and($nonce)->not->toBe($nonce2);
});

test('generate nonce -> with previous', function () {
    $testCrypto = new TestTraitImpl();
    $nonce = $testCrypto->generateNonce();
    $nonce2 = $testCrypto->generateNonce($nonce);

    expect($nonce)->toBeString()
        ->and(strlen($nonce))
        ->toBe(16)
        ->and($nonce2)
        ->toBeString()
        ->and(strlen($nonce2))
        ->toBe(16)
        ->and(ord($nonce[0]) + 1)->toBe(ord($nonce2[0]))
        ->and(substr($nonce, 1))->toBe(substr($nonce2, 1));
});