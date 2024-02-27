# Laravel Crypto

[![Run Tests](https://github.com/dmalusev/laravel-crypto/actions/workflows/test.yml/badge.svg?branch=master)](https://github.com/dmalusev/laravel-crypto/actions/workflows/test.yml)
[![GitHub issues](https://img.shields.io/github/issues/malusev998/LaravelCrypto?label=Github%20Issues)](https://github.com/malusev998/LaravelCrypto/issues)
[![GitHub stars](https://img.shields.io/github/stars/malusev998/LaravelCrypto?label=Github%20Stars)](https://github.com/malusev998/LaravelCrypto/stargazers)
[![GitHub license](https://img.shields.io/github/license/malusev998/LaravelCrypto?label=Licence)](https://github.com/malusev998/LaravelCrypto)

## What's Laravel Crypto and why should I use it?

Laravel Crypto is a library that provides easy to use API for most common cryptographic functions.
It is designed to be easy to use and secure. It uses the best and most secure algorithms available today.

Laravel's default encryption is secure, but it is slow. Laravel Crypto provides faster and more secure algorithms for
encryption and hashing.
It's drop in replacement for Laravel's `EncryptionServiceProvider` and it uses `libsodium` under the hood.
As long as you use default laravel encryption, you don't need to change anything in your code.

## Getting started

### Installing

```shell script
composer require codelieutenant/laravel-crypto
```

### Publishing config file

```shell script
php artisan vendor:publish --provider="CodeLieutenant\LaravelCrypto\ServiceProvider"
```

### Replacing Laravel's EncryptionServiceProvider with LaravelCrypto's ServiceProvider

In order to activate this package, you need to replace Laravel's `EncryptionServiceProvider`
with `LaravelCryptoServiceProvider`.

In `config/app.php` replace `Illuminate\Encryption\EncryptionServiceProvider::class`
with `CodeLieutenant\LaravelCrypto\ServiceProvider::class`
Depending on the laravel version you are using, you can do it in two ways.

Laravel 9.0 and above:

```php
use CodeLieutenant\LaravelCrypto\ServiceProvider as LaravelCryptoServiceProvider;
use Illuminate\Encryption\EncryptionServiceProvider as LaravelEncryptionServiceProvider;

// ...
'providers' => ServiceProvider::defaultProviders()
+    ->replace([
+        LaravelEncryptionServiceProvider::class => LaravelCryptoServiceProvider::class,
+    ])
    ->merge([
     // ...
    ])
    ->toArray(),

// ...
```

Laravel 8.0:

```php
use CodeLieutenant\LaravelCrypto\ServiceProvider as LaravelCryptoServiceProvider;

'providers' => [
    // ...
-   Illuminate\Encryption\EncryptionServiceProvider::class,
+   LaravelCryptoServiceProvider::class,
    // ...
],
```

### Configuration

In order to use Laravel Crypto, you need to change `cipher` in the `config/app.php` file.
Possible values:

**Unique to Laravel Crypto:**

- Sodium_AES256GCM
- Sodium_XChaCha20Poly1305
- Sodium_AEGIS128 (Planned on php8.4)
- Sodium_AEGIS256 (Planned on php8.4)

**Coming from Laravel Encryption (supported as LaravelCrypto falls back to EncryptionServiceProvider implementation):**

- AES-256-GCM
- AES-128-GCM
- AES-256-CBC (default)
- AES-128-CBC

```php
'cipher' => 'Sodium_AES256GCM',
```

### Generating Keys

For encryption Laravel command `php artisan key:generate` is good and can be used, but since this package
can be used for hashing and signing the data, command for generating keys is provided.

```shell script
php artisan crypto:keys
```

It generates backwards compatible keys for laravel `cipher` configuration and keys for `Sodium` algorithms.
There are multiple option for this command, you can check them by running `php artisan crypto:keys --help`,
so this command can be used as a drop in replacement for `key:generate`.

### Using in existing laravel project

This package does not provide backward compatibility with Laravel's default encryption (if configuration is changed).
If you want to use Laravel Crypto in an existing project, you need to re-encrypt all your data.
