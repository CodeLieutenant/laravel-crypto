<?php


namespace BrosSquad\LaravelCrypto;


use SodiumException;
use Psr\Container\ContainerInterface;
use BrosSquad\LaravelCrypto\Contracts\Hashing;
use BrosSquad\LaravelCrypto\Common\{
    Sha256,
    Sha512,
    Blake2b
};

class HashingManager implements Hashing
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function equals(string $hash1, string $hash2): bool
    {
        if (function_exists('sodium_memcmp')) {
            try {
                return sodium_memcmp($hash1, $hash2) === 0;
            } catch (SodiumException $e) {
                return false;
            }
        }

        return hash_equals($hash1, $hash2);
    }

    public function hash(string $data): ?string
    {
        return $this->container->get(Blake2b::class)->hash($data);
    }

    public function hashRaw(string $data): ?string
    {
        return $this->container->get(Blake2b::class)->hashRaw($data);
    }

    public function sha256(string $data): string
    {
        return $this->container->get(Sha256::class)->hash($data);
    }

    public function sha256Raw(string $data): string
    {
        return $this->container->get(Sha256::class)->hashRaw($data);
    }

    public function sha512(string $data): string
    {
        return $this->container->get(Sha512::class)->hash($data);
    }

    public function sha512Raw(string $data): string
    {
        return $this->container->get(Sha512::class)->hashRaw($data);
    }


    public function verify(string $hash, string $data): bool
    {
        return $this->container->get(Blake2b::class)->verify($hash, $data);
    }

    public function verifyRaw(string $hash, string $data): bool
    {
        return $this->container->get(Blake2b::class)->verifyRaw($hash, $data);
    }

    public function sha256Verify(string $hash, string $data): bool
    {
        return $this->container->get(Sha256::class)->verify($hash, $data);
    }

    public function sha256VerifyRaw(string $hash, string $data): bool
    {
        return $this->container->get(Sha256::class)->verifyRaw($hash, $data);
    }

    public function sha512Verify(string $hash, string $data): bool
    {
        return $this->container->get(Sha512::class)->verify($hash, $data);
    }

    public function sha512VerifyRaw(string $hash, string $data): bool
    {
        return $this->container->get(Sha512::class)->verifyRaw($hash, $data);
    }
}
