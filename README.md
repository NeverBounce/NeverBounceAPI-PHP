NeverBounce API PHP Wrapper
===========================

**This branch if for a yet unreleased version of the API. Please use the version of the wrapper in the master branch**

This is the official NeverBounce API PHP wrapper. It provides helpful methods to quickly implement our API in your existing php applications.
###Requirements
* PHP 5.5 or greater
* PHP JSON extension
* PHP cURL extension

Be sure to familiarize yourself with the API by visiting the [documentation](http://docs.neverbounce.com).

Installation
============
Composer Installation
---
This package takes advantage of composer's autoloading features, following the PSR-4 guidelines.

To install using composer you can run
``` bash
composer require "neverbounce/neverbounce-php":"~4.0.0"
```

Or add this to your composer.json
``` javascript
{
  "require": {
    "neverbounce/neverbounce-php":"~4.0.0"
  }
}
```
and run `composer install`
Manual Installation
---
You can clone the repo directly and include the files yourself via an auto loader. Keep in mind that this package does follow the PSR-4 spec.
```git clone https://github.com/creavos/NeverBounceAPI-PHP```