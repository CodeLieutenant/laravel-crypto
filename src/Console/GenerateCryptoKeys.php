<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Console;

use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSA;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command;
use Throwable;

class GenerateCryptoKeys extends Command
{
    protected $signature = 'crypto:keys';
    protected $description = 'Generates public and private keys for EdDSA(Ed25519) signature algorithm';

    public function handle(Config $config): int
    {
        try {
            EdDSA::generateKeys(
                $config->get('crypto.public_key_crypto.eddsa.private_key'),
                $config->get('crypto.public_key_crypto.eddsa.public_key')
            );
            $this->output->writeln('EdDSA(Ed25519) public and private key have been successfully generated');
            return 0;
        } catch (Throwable $e) {
            $this->output->error($e->getMessage());
            return 1;
        }
    }
}
