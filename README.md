NeverBounce API PHP Wrapper
===========================

This is the official NeverBounce API PHP wrapper. It provides helpful methods to quickly implement our API in your existing php applications.
###Requirements
* PHP 5.4 or greater
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
composer require "neverbounce/neverbounce-php":3.0.x
```

Or add this to your composer.json
``` javascript
{
  "require": {
    "neverbounce/neverbounce-php":3.0.x
  }
}
```
and run `composer install`
Manual Installation
---
You can clone the repo directly and include the files yourself via an auto loader. Keep in mind that this package does follow the PSR-4 spec.
```git clone https://github.com/creavos/NeverBounceAPI-PHP```

Usage
=====
Authentication
--------------
With our PHP wrapper authentication can be done by calling the `NB_Auth` class and running the `auth` method. Don't worry about passing a router or version if you do not have these details. Make sure to do this before making any requests with the wrapper or you will receive an authentication error. Placing this in the startup or config of your application is recommended.

``` PHP
// API_KEY Your API secret key
// APP_ID Your API app id
// ROUTER Your API sub domain http://<route>.neverbounce.com
// VERSION The api version to use
\NeverBounce\API\NB_Auth\NB_Auth::auth(<API_KEY>, <APP_ID>, [<ROUTER>, [<VERSION>]]);
```

Single
------
Once you've authenticated you can use the endpoints freely. To validate an email use the `verify` method in the `NB_Single` class. The result is stored in the `response` property in this class, you can also find the raw JSON response in the `response_raw` property. We have included two additional methods in this class so you can easily check the result or get a human readable result.

``` PHP
// Verify an email
$email = \NeverBounce\API\NB_Auth\NB_Single::verify(<EMAIL>);
var_dump($email->response);
```
``` PHP
object(stdClass)#5 (4) {
  ["success"]=>bool(true)
  ["result"]=>int(0)
  ["result_details"]=>int(0)
  ["execution_time"]=>float(1.5955789089203)
}
```

###is()
This method will check to see if the the validation result matches the desired result code and will return either true or false. It accepts result codes in either string, integer or array formats. 

``` PHP
$email = \NeverBounce\API\NB_Auth\NB_Single::verify(<EMAIL>);

// Returns true if email is valid
$email->is('valid');
$email->is(0);

// Returns true if email is valid or catchall
$email->is(['valid', 'catchall']);
$email->is([0,3]);
```

####Accepted Codes
| text | integer |
|------|---------|
|valid|0|
|good (alias)|0|
|invalid|1|
|bad (alias)|1|
|disposable|2|
|catchall|3|
|unknown|4|

###defintion()
This method returns a human readable string for the validation result. The strings returned are 'Valid', 'Invalid', 'Disposable', 'Catchall', and 'Unknown'.

``` PHP
$email = \NeverBounce\API\NB_Auth\NB_Single::verify(<EMAIL>);

// Returns 'Valid' if email is valid
$email->definition();
```

Changes
=======
#####v3.0.0
* With version 3.0 of the API, we have introduced OAuth 2.0 to replace our authentication system. For this reason we have discontinued both v1.x and v2.x APIs.