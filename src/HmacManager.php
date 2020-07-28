<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelHashing;

use Psr\Container\ContainerInterface;
use BrosSquad\LaravelHashing\Contracts\Hmac;
use BrosSquad\LaravelHashing\Hmac\{
    Hmac256,
    Hmac512
};

class HmacManager implements Hmac
{
    /**
     * @var \BrosSquad\LaravelHashing\Contracts\Hmac
     */
    protected $hmac;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    public function __construct(Hmac $hmac, ContainerInterface $container)
    {
        $this->hmac = $hmac;
        $this->container = $container;
    }

    public function sign(string $data): ?string
    {
        return $this->hmac->sign($data);
    }

    public function verify(string $message, string $hmac): bool
    {
        return $this->hmac->verify($message, $hmac);
    }

    public function hmac256Sign(string $data): string
    {
        return $this->container->get(Hmac256::class)->sign($data);
    }

    public function hmac512Sign(string $data): string
    {
        return $this->container->get(Hmac512::class)->sign($data);
    }

    public function hmac256Verify(string $message, string $hmac): bool
    {
        return $this->container->get(Hmac256::class)->verify($message, $hmac);
    }

    public function hmac512Verify(string $message, string $hmac): bool
    {
        return $this->container->get(Hmac512::class)->verify($message, $hmac);
    }

}
