<?php


namespace BrosSquad\LaravelHashing;


use BrosSquad\LaravelHashing\Contracts\Hmac;

class HmacManager implements Hmac
{
    /**
     * @var \BrosSquad\LaravelHashing\Contracts\Hmac
     */
    protected $hmac;

    public function __construct(Hmac $hmac)
    {
        $this->hmac = $hmac;
    }

    public function sign(string $data): ?string
    {
        return $this->hmac->sign($data);
    }

    public function verify(string $message, string $hmac): bool
    {
        return $this->hmac->verify($message, $hmac);
    }
}
