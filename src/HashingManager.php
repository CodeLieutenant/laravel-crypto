<?php


namespace BrosSquad\LaravelHashing;


use SodiumException;
use Psr\Container\ContainerInterface;
use BrosSquad\LaravelHashing\Contracts\Hashing;
use BrosSquad\LaravelHashing\Common\{
    Sha256,
    Sha512,
    Blake2b
};

class HashingManager implements Hashing
{
    /**
     * @var \Psr\Container\ContainerInterface
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


    public function sha256(string $data): string
    {
        return $this->container->get(Sha256::class)->hash($data);
    }

    public function sha512(string $data): string
    {
        return $this->container->get(Sha512::class)->hash($data);
    }

}
