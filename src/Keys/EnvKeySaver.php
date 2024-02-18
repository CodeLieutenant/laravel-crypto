<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use BrosSquad\LaravelCrypto\Support\Base64;
use RuntimeException;

trait EnvKeySaver
{
    protected function writeNewEnvironmentFileWith(string $file, array $values): void
    {
        $input = @file_get_contents($file);

        if ($input === false) {
            throw new RuntimeException('Error while reading environment file: ' . $file);
        }

        $match = [];
        $replacement = [];

        foreach ($values as $env => $value) {
            $match[] = $this->keyReplacementPattern($env, $value['old']);
            $replacement[] = $env . '=' . $value['new'];
        }

        $replaced = preg_replace($match, $replacement, $input);

        if ($replaced === $input || $replaced === null) {
            $replaced = $input;

            foreach ($values as $env => $value) {
                $replaced .= "\n" . $env . '=' . $value['new'];
            }

            $replaced .= "\n";
        }

        if (@file_put_contents($file, $replaced) === false) {
            throw new RuntimeException('Error while writing environment file: ' . $file);
        }
    }

    protected function keyReplacementPattern(string $env, string $value): string
    {
        $env = preg_quote($env, '/');
        $value = preg_quote($value, '/');

        return "/^$env=$value/m";
    }

    protected function formatKey(string $key): string
    {
        return 'base64:' . Base64::encode($key);
    }
}