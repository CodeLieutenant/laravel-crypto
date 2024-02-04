<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Console;

use BrosSquad\LaravelCrypto\Keys\AppKey;
use BrosSquad\LaravelCrypto\Keys\Blake2bHashingKey;
use BrosSquad\LaravelCrypto\Keys\EdDSASignerKey;
use BrosSquad\LaravelCrypto\Keys\HmacKey;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'crypto:keys')]
class GenerateCryptoKeysCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'crypto:keys
        {--force   : Force the operation to run when in production}
        {--eddsa   : Generate EdDSA(Ed25519) public and private key}
        {--show    : Display the key instead of modifying files}
        {--app     : Generate application key}
        {--blake2b : Generate blake2b hashing key}
        {--hmac    : Generate Hmac key}';

    protected $description = 'Generate crypto keys (APP_KEY, EdDSA, BLAKE2B_HASHING_CRYPTO_KEY)';

    public function handle(
        EdDSASignerKey $edDSAGenerator,
        AppKey $appKeyGenerator,
        Blake2bHashingKey $blake2bKeyGenerator,
        HmacKey $hmacKeyGenerator,
    ): int {
        $show = $this->option('show') ?? false;
        $eddsa = $this->option('eddsa') ?? true;
        $app = $this->option('app') ?? true;
        $blake2b = $this->option('blake2b') ?? true;
        $hmac = $this->option('hmac') ?? true;

        $proceed = $this->confirmToProceed('This operation will overwrite existing keys', function () {
            return $this->getLaravel()->environment() === 'production';
        });

        if (!$proceed) {
            return self::FAILURE;
        }

        try {
            if ($eddsa) {
                $eddsaKey = $edDSAGenerator->generate(!$show);

                if ($show) {
                    $this->info('EdDSA Key: ' . $eddsaKey);
                }
            }

            if ($app) {
                $appKey = $appKeyGenerator->generate(!$show);

                if ($show) {
                    $this->info('App Key: ' . $appKey);
                }
            }

            if ($blake2b) {
                $blake2bKey = $blake2bKeyGenerator->generate(!$show);

                if ($show) {
                    $this->info('Blake2b Key: ' . $blake2bKey);
                }
            }

            if ($hmac) {
                $hmacKey = $hmacKeyGenerator->generate(!$show);

                if ($show) {
                    $this->info('HMAC Key: ' . $hmacKey);
                }
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
