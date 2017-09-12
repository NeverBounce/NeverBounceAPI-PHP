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
* PHP 5.5 or greater
* PHP JSON extension
* PHP cURL extension

Installation
============
Composer Installation
---
This package takes advantage of composer's autoloading features, following the PSR-4 guidelines.

To install using composer you can run
``` bash
composer require "neverbounce/neverbounce-php":"~4.1.0"
```

Or add this to your composer.json
``` php
{
  "require": {
    "neverbounce/neverbounce-php":"~4.1.0"
  }
}
```
and run `composer install`

Manual Installation
---
You can clone the repo directly and include the files yourself via an auto loader. Keep in mind that this package does follow the PSR-4 spec.
```git clone https://github.com/creavos/NeverBounceAPI-PHP```