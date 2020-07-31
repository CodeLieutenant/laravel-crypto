<?php


namespace BrosSquad\LaravelCrypto\Console;


use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSAManager;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Throwable;

class GenerateCryptoKeys extends Command
{
    protected $signature = 'crypto:keys';
    protected $description = 'Generates public and private keys for EdDSA(Ed25519) signature algorithm';

    public function handle(Repository $config): int
    {
        try {
            EdDSAManager::generateKeys(
                $config->get('crypto.private_key'),
                $config->get('crypto.public_key')
            );
            $this->output->writeln('EdDSA(Ed25519) public and private key have been successfully generated');
            return 0;
        } catch (Throwable $e) {
            $this->output->error($e->getMessage());
            return 1;
        }

    }
}
