**This version is defunct, please check the 2.0.x-dev branch for the latest version**

---

NeverBounceAPI-PHP
==================

This package provides convenient methods to integrate the NeverBounce API into your project.

Be sure to familiarize yourself with the API by visiting the [documentation](http://docs.neverbounce.com).
Composer Installation
---
This package takes advantage of composer's autoloading features, following the PSR-4 guidlines.

To install using composer you can run
```
composer require "neverbounce/neverbounce-php":1.0
```

Or add this to your composer.json
```
{
  "require": {
    "neverbounce/neverbounce-php":1.0
  }
}
```
and run `composer install`
Manual Installation
---
You can clone the repo directly and include the files yourself via an auto loader. Keep in mind that this package does follow the PSR-4 spec.
```git clone https://github.com/creavos/NeverBounceAPI-PHP```
