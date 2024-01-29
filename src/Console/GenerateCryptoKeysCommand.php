<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Console;

use BrosSquad\LaravelCrypto\Keys\AppKeyLoader;
use BrosSquad\LaravelCrypto\Keys\Blake2bKeyGenerator;
use BrosSquad\LaravelCrypto\Keys\EdDSASignerKeyLoader;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(name: 'crypto:key')]
class GenerateCryptoKeysCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'crypto:key
        {--force   : Force the operation to run when in production}
        {--eddsa   : Generate EdDSA(Ed25519) public and private key}
        {--show    : Display the key instead of modifying files}
        {--app     : Generate application key}
        {--blake2b : Generate BLAKE2B_HASHING_CRYPTO_KEY}';

    protected $description = 'Generate crypto keys (APP_KEY, EdDSA, BLAKE2B_HASHING_CRYPTO_KEY)';

    public function handle(
        EdDSASignerKeyLoader $edDSAGenerator,
        AppKeyLoader $appKeyGenerator,
        Blake2bKeyGenerator $blake2bKeyGenerator,
    ): int {
        $show = $this->option('show') ?? false;
        $eddsa = $this->option('eddsa') ?? true;
        $app = $this->option('app') ?? true;
        $blake2b = $this->option('blake2b') ?? true;

        try {
            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('An error occurred while generating keys');
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
