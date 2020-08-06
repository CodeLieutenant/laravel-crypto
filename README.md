# Laravel General Hashing
> Wrapper classes for common hashing functions

[![Build Status](https://dev.azure.com/BrosSquad/LaravelHashing/_apis/build/status/malusev998.LaravelHashing?branchName=master)](https://dev.azure.com/BrosSquad/LaravelHashing/_build/latest?definitionId=8&branchName=master)
[![GitHub issues](https://img.shields.io/github/issues/malusev998/LaravelCrypto?label=Github%20Issues)](https://github.com/malusev998/LaravelCrypto/issues)
[![GitHub stars](https://img.shields.io/github/stars/malusev998/LaravelCrypto?label=Github%20Stars)](https://github.com/malusev998/LaravelCrypto/stargazers)
[![GitHub license](https://img.shields.io/github/license/malusev998/LaravelCrypto?label=Licence)](https://github.com/malusev998/LaravelCrypto)


- [Laravel General Hashing](#laravel-general-hashing)
  - [Introduction](#introduction)
  - [Getting started](#getting-started)
    - [Installing](#installing)
    - [Publishing config file](#publishing-config-file)
    - [Generating EdDSA private and public key](#generating-eddsa-private-and-public-key)
  - [Utilities](#utilities)
    - [Encoding](#encoding)
      - [Base64 Encoding](#base64-encoding)
    - [Generating random data](#generating-random-data)
  - [General Hashing](#general-hashing)
      - [Using facade](#using-facade)
      - [Using dependency injection](#using-dependency-injection)
  - [Shared Key signatures](#shared-key-signatures)
    - [Using Facade](#using-facade-1)
    - [Using Dependency Injection](#using-dependency-injection-1)
  - [Public Key signatures](#public-key-signatures)
    - [Using Facade](#using-facade-2)
    - [Using Dependency Injection](#using-dependency-injection-2)
  - [Advanced](#advanced)
    - [Encryption](#encryption)
    - [SHA256](#sha256)
      - [Using Hashing Facade](#using-hashing-facade)
      - [Using Dependency Injection](#using-dependency-injection-3)
    - [SHA512](#sha512)
      - [Using Hashing Facade](#using-hashing-facade-1)
      - [Using Dependency Injection](#using-dependency-injection-4)

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

## Utilities

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

## General Hashing

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

#### Using dependency injection

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

## Shared Key signatures

Shared key signatures are done using SHA512/256 HMAC. 
(Sha512/256 -> SHA512 is optimized for X86_64 architecture which most computers and servers run these day, but SHA512 is to long, so it's trimmed to 256bits (64 bytes) hence the name SHA512/256, offers same security as SHA512 but it's shorter)

Read more on [HMAC](https://en.wikipedia.org/wiki/HMAC)

### Using Facade

```php

namespace App\Service;

use BrosSquad\LaravelCrypto\Facades\Hmac;

class Service 
{
    public function createSignature()
    {
        $data = 'Hello World';

        $signature = Hmac::sign($data); // Base64 Encoded encoded signature

        $signature = Hmac::signRaw($data); // Raw bytes for signature
        
        // Rest of the code
    }

    public function verifySigunature(string $signature) 
    {
        $data = 'Hello World';
        
        if(Hmac::verify($data, $signature)) {
            // Signature is valid
        } else {
            // Signature is invalid
        }
    }
}

```

### Using Dependency Injection

```php
namespace App\Service;

use BrosSquad\LaravelCrypto\Contracts\Signing;

class Service 
{
    private Signing $hmac;

    public function __construct(Signing $hmac) 
    {
        $this->hmac = $hmac;
    }

    public function createSignature()
    {
        $data = 'Hello World';

        $signature = $this->hmac->sign($data); // Base64 Encoded encoded signature

        $signature = $this->hmac->signRaw($data); // Raw bytes for signature
        
        // Rest of the code
    }

    public function verifySigunature(string $signature) 
    {
        $data = 'Hello World';
        
        if($this->hmac->verify($data, $signature)) {
            // Signature is valid
        } else {
            // Signature is invalid
        }
    }
}

```

## Public Key signatures

Public key signing uses state of the art in public key cryptography -> EdDSA or Ed25519 Algorithms developed by famous crytographer Daniel Bernstein. It is based on Edwards Curve, and it is much faster then RSA (many even more serure). Public and privete keys are short, this allows the algorithm to be much faster. When ever you can use EdDSA algorithm for public key signatures

**Before you start using EdDSA, generate private and public keys with artisan console command ```$ php artisan crypto:keys ```**


### Using Facade

```php

namespace App\Service;

use BrosSquad\LaravelCrypto\Facades\EdDSA;

class Service 
{
    public function createSignature()
    {
        $data = 'Hello World';

        $signature = EdDSA::sign($data); // Base64 Encoded encoded signature

        $signature = EdDSA::signRaw($data); // Raw bytes for signature
        
        // Rest of the code
    }

    public function verifySigunature(string $signature) 
    {
        $data = 'Hello World';
        
        if(EdDSA::verify($data, $signature)) {
            // Signature is valid
        } else {
            // Signature is invalid
        }
    }
}

```

### Using Dependency Injection


```php
namespace App\Service;

use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;

class Service 
{
    private PublicKeySigning $signing;

    public function __construct(PublicKeySigning $signing) 
    {
        $this->signing = $signing;
    }

    public function createSignature()
    {
        $data = 'Hello World';

        $signature = $this->signing->sign($data); // Base64 Encoded encoded signature

        $signature = $this->hmac->signRaw($data); // Raw bytes for signature
        
        // Rest of the code
    }

    public function verifySigunature(string $signature) 
    {
        $data = 'Hello World';
        
        if($this->hmac->verify($data, $signature)) {
            // Signature is valid
        } else {
            // Signature is invalid
        }
    }
}

```

## Advanced

### Encryption

LaravelCrypto library provides **2 additional encryption algorithms**. It uses default laravel Encrypter interface and key so it does not require any code change, exept in config file 

**Use this only in new applications.** 

> Use in older applications

**If you have application which uses laravel default encryption and you have stored encrypted data in database, you will need to reencrypt the data with new algorithm!**

```php
// app.cofig
return [
    // ...
    
    // XChaCha20Poly1305 Algorithm
    'cipher' => 'XChaCha20Poly1305',
    
    // AES 256 GCM
    //!! Make sure you have hardware acceleration for
    //  AES-256-GCM, it wont work if your sever does not support it !!
    'cipher' => 'AES-256-GCM',
    

    // .. 
]

```

###  SHA256

#### Using Hashing Facade

#### Using Dependency Injection

###  SHA512

#### Using Hashing Facade

#### Using Dependency Injection
