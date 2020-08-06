# Laravel General Hashing
> Wrapper classes for common hashing functions

[![Build Status](https://dev.azure.com/BrosSquad/LaravelHashing/_apis/build/status/malusev998.LaravelHashing?branchName=master)](https://dev.azure.com/BrosSquad/LaravelHashing/_build/latest?definitionId=8&branchName=master)
[![GitHub issues](https://img.shields.io/github/issues/malusev998/LaravelCrypto?label=Github%20Issues)](https://github.com/malusev998/LaravelCrypto/issues)
[![GitHub stars](https://img.shields.io/github/stars/malusev998/LaravelCrypto?label=Github%20Stars)](https://github.com/malusev998/LaravelCrypto/stargazers)
[![GitHub license](https://img.shields.io/github/license/malusev998/LaravelCrypto?label=Licence)](https://github.com/malusev998/LaravelCrypto)


## Introduction

Many web applications use some kind of cryptography, but most programmers do not know what algorithm to use.

There are a lot of choices, but most of them are terrible these days **(Looking at MD4 and MD5)**. Most of the good
functions have really confusing API (like libsodium), so I wanted a clean "Laravel Way" and OOP access to these API's. 
 I chose to write Laravel library (because Laravel is most commonly used php framework) that will help programmers
  (especially new ones) when they need cryptography.
  
This library provides **Hashing** and **Signature** algorithms with easy to use API.


## Getting started

### Installing

```shell script
$ composer require brossquad/laravel-crypto
```
### Publishing config file

```shell script
php artisan vendor:publish --provider="BrosSquad\LaravelCrypto\HashingServiceProvider"
```

### Generating EdDSA private and public key

Artisan command will generate private and public key inside ```PUBLIC_CRYPTO_PRIVATE_KEY``` and ```PUBLIC_CRYPTO_PUBLIC_KEY``` environmental variables (defaults to ```storage_path('crypto_public|private.key'))```

```shell script
$ php artisan crypto:keys
```

### Encoding

#### Base64 Encoding

```php
use BrosSquad\LaravelCrypto\Facades\Base64;

$binaryData = random_bytes(32);

// STANDARD VERSION

// Standard encoding
$base64 = Base64::encode($binaryData);
 
// Url encoding
$base64 = Base64::urlEncode($binaryData);

// Standard decoding
$binary = Base64::decode($base64);

// Url decoding
$binary = Base64::urlDecode($base64);


// CONSTANT TIME ENCODING AND DECODING

// Standard encoding
$base64 = Base64::constantEncode($binaryData);

// Url encoding
$base64 = Base64::constantUrlEncode($binaryData);

// Url encoding with no padding (=)
$base64 = Base64::constantUrlEncodeNoPadding($binaryData);

// Standard decoding
$binary = Base64::constantDecode($base64);

// Url decoding
$binary = Base64::constantUrlDecode($base64);

// Url decoding with no padding
$binary = Base64::constantUrlDecodeNoPadding($base64);



// LENGTH CHECKING

// Get maximum length for the byte buffer from base64 encoded string 
$bufferLength = Base64::maxEncodedLengthToBytes($base64Length);

// Get exact length for byte buffer from base64 encoded
$bufferLength = Base64::encodedLengthToBytes($base64String);

// Get base64 string length from byte buffer length
$base64Length = Base64::encodedLength($bufferLength, $hasPadding);
```

### Generating random data

```php
use BrosSquad\LaravelCrypto\Facades\Random;

// Generate random string
$randomString = Random::string(60); // Generates random string base64 url encoded with no padding 

// Generate random bytes
$randomBytes = Random::bytes(32); // Generates buffer filled with crypto secure random bytes

// Generate random int
$randomImt = Random::int($min, $max);
```

### Hashing

Laravel crypto library uses the latest and best hashing algorithms. (Blake2b)

#### Using facade

```php
namespace App\Services;

use BrosSquad\LaravelCrypto\Facades\Hashing;

class Service 
{
    public function hashing(): void
    {
        $data = 'Hello World';
        $blake2bHash = Hashing::hash($data); // Base64 Encoded string
        $blake2HashBinary = Hashing::hashRaw($data); // Binary Data, outputs 64 bytes of Blake2 hash
        // To check the length of binary strings use mb_strlen($binary, '8bit') function
    }
    
    public function checkingHash(): void
    {
        $hash1 = Hashing::hash('String 1');
        $hash2 = Hashing::hash('String 2');
        // Uses constant time compare to check 
        // if two hashes are equal
        // !! It supports binary data !!
        if(Hashing::equals($hash1, $hash2)) {
            // Hashes are equal
        }
    }

    public function hashVerification(): void {
        $data = 'Hello World';
        $hash = Hashing::hash($data);
        
        // !! Does not support binary hash !!
        // !! For binary data use verifyRaw !!
        if(Hashing::verify($hash,$data)) {
            // When data is hashed, hashes are same
        }
    }
}
```

#### Using dependency injection (prefered)

> When you use dependency injection, it will always use best algorithm possible. It removes room for errors;

```php
namespace App\Services;

use \BrosSquad\LaravelCrypto\Contracts\Hashing;

class Service 
{
    protected Hashing $hashing;

    public function __construct(Hashing $hashing) 
    {
        $this->hashing = $hashing;        
    }

    public function hashing(): void
    {
        $data = 'Hello World';
        $blake2bHash = $this->hashing->hash($data); // Base64 Encoded string
        $blake2HashBinary = $this->hashing->hashRaw($data); // Binary Data, outputs 64 bytes of Blake2 hash
        // To check the length of binary strings use mb_strlen($binary, '8bit') function
    }
    
    public function checkingHash(): void
    {
        $hash1 = $this->hashing->hash('String 1');
        $hash2 = $this->hashing->hash('String 2');
        // Uses constant time compare to check 
        // if two hashes are equal
        // !! It supports binary data !!
        if($this->hashing->equals($hash1, $hash2)) {
            // Hashes are equal
        }
    }

    public function hashVerification(): void {
        $data = 'Hello World';
        $hash = $this->hashing->hash($data);
        
        // !! Does not support binary hash !!
        // !! For binary data use verifyRaw !!
        if($this->hashing->verify($hash,$data)) {
            // When data is hashed, hashes are same
        }
    }
}
```


**When ever possible use default hashing algorithm (BLAKE2B). It is the most secure hash now, and it is faster than any other currently is use in industry (Except BLAKE3 which is not implemented in libsodium yet).**

Default hashing API is wrapper on libsodium function. It provides nice API to work with in Laravel projects.

**These functions should not be used for password hashing. NEVER!! For password hashing use laravel default Hash facade or Hasher interface**
