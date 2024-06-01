# KiriminWa API

Official wrapper for kiriminwa.id

## Table Of Contents

- [KiriminWa API](#kiriminwa-api)
  - [Table Of Contents](#table-of-contents)
  - [Installation](#installation)
    - [Laravel](#laravel)
  - [Usage](#usage)
  - [Laravel Usage](#laravel-usage)

## Installation

install the package

```bash
composer require dekate/kiriminwa
```

### Laravel

publish the config file to be used in Laravel project

```bash
php artisan vendor:publish --tag=kiriminwa
```

add `KIRIMIN_WA_API_KEY` to your .env

## Usage

To use the package, create an instance of either `KiriminWa/Whatsapp`, `KiriminWa/Sms`, or `KiriminWa/Email` depending on your implementation

Ensure the receiver's number is composed solely of digits, begins with a country code, and doesn’t start with 0. It should not contain any symbols, whether they are plus signs, spaces, or dashes.

```php
use KiriminWa/Whatsapp;
use KiriminWa/Sms;
use KiriminWa/Email;

// ...

$whatsappClient = new Whatsapp("MY_API_KEY");
$smsClient = new Whatsapp("MY_API_KEY");
$emailClient = new Whatsapp("MY_API_KEY");

$resultWhatsapp = $whatsappClient->sendMessage('880123456789', 'Hello World!');
$resultSms = $smsClient->sendSms('880123456789', 'Hello World!');
$resultEmail = $emailClient->sendEmail("receiver@email.com", "Subject", "Message", "sender", "demo@example.com")

$resultBody = $result->body;
```

## Laravel Usage

```php
use KiriminWa/Facade/Whatsapp;
use KiriminWa/Facade/Sms;
use KiriminWa/Facade/Email;

// ...

$resultWhatsapp = Whatsapp::sendMessage('880123456789', 'Hello World!');
$resultSms = Sms::sendSms('880123456789', 'Hello World!');
$resultEmail = Email::sendEmail("receiver@email.com", "Subject", "Message", "sender", "demo@example.com")
```
