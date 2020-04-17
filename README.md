<p align="center"><img src="https://neverbounce-marketing.s3.amazonaws.com/neverbounce_color_600px.png"></p>

<p align="center">
<a href="https://travis-ci.org/NeverBounce/NeverBounceAPI-PHP"><img src="https://travis-ci.org/NeverBounce/NeverBounceAPI-PHP.svg" alt="Build Status"></a>
<a href="https://codeclimate.com/github/NeverBounce/NeverBounceAPI-PHP"><img src="https://codeclimate.com/github/NeverBounce/NeverBounceAPI-PHP/badges/gpa.svg" /></a>
<a href="https://packagist.org/packages/neverbounce/neverbounce-php"><img src="https://img.shields.io/packagist/v/neverbounce/neverbounce-php.svg" /></a>
<a href="https://packagist.org/packages/neverbounce/neverbounce-php"><img src="https://img.shields.io/packagist/dm/neverbounce/neverbounce-php.svg" /></a>
</p>

> Looking for the V3 API wrapper? Click [here](https://github.com/NeverBounce/NeverBounceAPI-PHP/tree/v3)

This is the official NeverBounce API PHP wrapper. It provides helpful methods to quickly implement our API in your existing php applications.

### Requirements
* PHP 7.1 or greater
* PHP JSON extension
* PHP cURL extension

> For PHP 5.5 - 7.0 use the [4.1](https://github.com/NeverBounce/NeverBounceAPI-PHP/tree/4.1) branch

Installation
============
Composer Installation
---
This package takes advantage of composer's autoloading features, following the PSR-4 guidelines.

To install using composer you can run
``` bash
composer require "neverbounce/neverbounce-php":"~4.4.0"
```

Or add this to your composer.json
``` php
{
  "require": {
    "neverbounce/neverbounce-php":"~4.4.0"
  }
}
```
and run `composer install`

Manual Installation
---
You can clone the repo directly and include the files yourself via an auto loader. Keep in mind that this package does follow the PSR-4 spec.
```git clone https://github.com/creavos/NeverBounceAPI-PHP```

Running Examples
---

First clone or download the repository to your local machine and install the composer dependencies. Once the composer dependencies are installed create a new file in the project's root directory called `.api-key.php` with the following contents (adding your own API key):

```php
<?php

$api_key = "secrete_api_key_goes_here";
``` 

With the API key file created you can now run the examples contained within the `example/` directory.

```php
php examples/account-info.php
```
