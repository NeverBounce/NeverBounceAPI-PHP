NeverBounceAPI-PHP
==================

**Major Change**

With version 2.0.x oAuth2 has been introduced into the API. We will be depreciating the 1.x libraries in favor of this version. As such the authorization procedure has changes slightly in this version

This package provides convenient methods to integrate the NeverBounce API into your project.

Be sure to familiarize yourself with the API by visiting the [documentation](http://docs.neverbounce.com).
Composer Installation

---
This package takes advantage of composer's autoloading features, following the PSR-4 guidlines.

To install using composer you can run
```
composer require "neverbounce/neverbounce-php":2.0.x
```

Or add this to your composer.json
```
{
  "require": {
    "neverbounce/neverbounce-php":2.0.x
  }
}
```
and run `composer install`
Manual Installation
---
You can clone the repo directly and include the files yourself via an auto loader. Keep in mind that this package does follow the PSR-4 spec.
```git clone https://github.com/creavos/NeverBounceAPI-PHP```