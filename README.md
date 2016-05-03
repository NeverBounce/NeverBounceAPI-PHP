NeverBounce API PHP Wrapper
===========================

This is the official NeverBounce API PHP wrapper. It provides helpful methods to quickly implement our API in your existing php applications.
###Requirements
* PHP 5.4 or greater
* PHP JSON extension
* PHP cURL extension

Be sure to familiarize yourself with the API by visiting the [documentation](https://neverbounce.com/help/api/getting-started-with-the-api/).

Installation
============
Composer Installation
---
This package takes advantage of composer's autoloading features, following the PSR-4 guidelines.

To install using composer you can run
``` bash
composer require "neverbounce/neverbounce-php":dev-master
```

Or add this to your composer.json
``` javascript
{
  "require": {
    "neverbounce/neverbounce-php":dev-master
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

Find your credetials [here](https://app.neverbounce.com/settings/api)

``` PHP
// API_KEY Your API username
// API_SECRET_KEY Your API secret key
// ROUTER Your API sub domain http://<route>.neverbounce.com
// VERSION The api version to use
\NeverBounce\API\NB_Auth::auth(<API_SECRET_KEY>, <API_KEY>, [<ROUTER>, [<VERSION>]]);
```

Single
------
Once you've authenticated you can use the endpoints freely. To validate an email use the `verify` method in the `NB_Single` class. The result is stored in the `response` property in this class, you can also find the raw JSON response in the `response_raw` property. We have included two additional methods in this class so you can easily check the result or get a human readable result.

``` PHP
// Verify an email
$email = \NeverBounce\API\NB_Single::app()->verify(<EMAIL>);
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
$email = \NeverBounce\API\NB_Single::app()->verify(<EMAIL>);

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
$email = \NeverBounce\API\NB_Single::app()->verify(<EMAIL>);

// Returns 'Valid' if email is valid
$email->definition();
```

Account
-------
The account class can be accessed by calling the `check` method within the `NB_Account` class. This will give you a quick overview of your account. As with the Single endpoint you can access the result via the `response` property and the raw JSON via the `response_raw` property. We have also provided a couple helpers to make it easier to extract the data.

``` PHP
$account = \NeverBounce\API\NB_Account::app()->check();
var_dump($account->response);
```
``` PHP
object(stdClass)#5 (5) {
  ["success"]=>bool(true)
  ["credits"]=>string(7) "9085992"
  ["jobs_completed"]=>string(2) "39"
  ["jobs_processing"]=>string(1) "4"
  ["execution_time"]=>float(0.064954996109009)
}
```

###balance()
This returns your current credit balance.

``` PHP
$account = \NeverBounce\API\NB_Account::app()->check();
var_dump($account->balance());
```

###jobs_completed()
This returns how many completed jobs that you have run through the bulk endpoint or through the dashboard

``` PHP
$account = \NeverBounce\API\NB_Account::app()->check();
var_dump($account->jobs_completed());
```

###jobs_processing()
This returns how many running jobs that you have processing through the bulk endpoint or through the dashboard

``` PHP
$account = \NeverBounce\API\NB_Account::app()->check();
var_dump($account->jobs_processing());
```

Bulk
----
The bulk class includes several methods for interacting with bulk validations, the same validations that are done by uploading a CSV to the dashboard.

###get()
This method returns all existing jobs in your account, including any and all jobs you have run through the dashboard. These jobs will be returned in ascending order from your earliest job to your latest job. The jobs can be accessed in the `jobs` property, or by using our helper functions to navigate this array. Each job is stored as a new `NB_Job` object in this array.

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->get();
var_dump($jobs->jobs);
```

###retrieve()
This method will return only the results of the jobs you specify. When you already know the id of the job or jobs you need to query for, this method will return the result faster. You can supply a single id as an integer or supply multiple ids in an array. These job(s) will be stored in the `jobs` property as `NB_Job` objects just as they would be using the `get` method. 

``` php
use \NeverBounce\API\NB_Bulk;
// Single ID
$job = NB_Bulk::app()->retrieve(<JOB_ID>);
var_dump($job->jobs);

// Multiple ID's
$jobs = NB_Bulk::app()->retrieve(array(<JOB_ID>,<JOB_ID>));
var_dump($jobs->jobs);
```

###all()
This will return the `jobs` array. It's the same as calling `NB_Bulk::app()->jobs`.

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->get();
var_dump($jobs->all());
```

###first()
This will return the first item in the `jobs` array. It's the same as `NB_Bulk::app()->jobs[0]`.

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->get();
var_dump($jobs->first());
```

###desc()
This will perform a `rsort` on the `jobs` property reversing the sorting of the array. Useful for when you want your newest job to appear first and oldest job to appear last in the array. This method returns itself so it is chainable with `first` or `all`. 

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->get();

// Returns newest job
var_dump($jobs->desc()->first());
```

###select()
This method will allow you to select a specific job from the `jobs` array by passing the desired job's ID. If the job doesn't exist it will return false.

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->get();
var_dump($jobs->select(<JOB_ID>));
```

###create()
This method allows you to start new bulk validation jobs. Input can be provided either directly by getting the contents of a file or by passing a URL for the file. You can also specify a filename for the job to appear as in the dashboard, if one isn't provided `uniqid()` will be used as the filename. This function will `fetch` the job details open success.

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->create(<INPUT>, <INPUT_LOCATION>[, <FILENAME>]);
var_dump($job->first());
```

###sample()
This method allows you to start a free analysis on your data. Read more about the list analysis (here)[https://docs.neverbounce.com/#methods-bulk-free-analysis]. This function takes the same parameters as the `create` method above. Once you have completed an analysis you can then run a full validation using the `start` method.

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->sample(<INPUT>, <INPUT_LOCATION>[, <FILENAME>]);
var_dump($job->first());
```

####Input & Input Location

The `bulk` endpoint allows email lists to be passed to it two different ways using the `input_location` parameter.
#####Remote URL

Using a remote url allows you to host the file and provide us with a direct link to it. This can be done with any protocol supported by cURL, we suggest using either `http`, `https`, `ftp`, `ftps` or `sftp`. When using a url that requires authentication be sure to pass the username and password in the actual url. When using `sftp` keep in mind that we do not currently support key based authentication.

``` php
use \NeverBounce\API\NB_Bulk;
$input = 'https://example.com/email_list.csv';
$job = NB_Bulk::app()->create($input, NB_Bulk::REMOTE_URL);
var_dump($job->first());
```

######Valid URLs
``` bash
# FTP With Authentication
ftp://name:passwd@example.com:21/full/path/to/file
# HTTP Request
http://example.com/full/path/to/file
# HTTP Basic Authentication
http://name:passwd@example.com/full/path/to/file
```

#####Supplied Data

Supplying the data directly gives you the option to dynamically create email lists on the fly. To do this either pass the emails separated with line breaks (`\r\n` or `\n`) or by passing csv formatted data (comma, semi-colon, space or tab delimeters). When using this method be sure that the data is HTTP encoded to prevent any mangling of the data.

``` php
use \NeverBounce\API\NB_Bulk;
$input = file_get_contents('email_list.csv');
$job = NB_Bulk::app()->create($input, NB_Bulk::SUPPLIED_DATA);
var_dump($job->first());
```

######Raw text Input (New lines)
``` bash
joe@example.com\n
alice@example.com\n
tom@example.com\n
```

######Raw CSV Input
``` bash
ID, Email, Name, Department\n
1, joe@example.com, Joe, Accounting\n
2, alice@example.com, Alice, Accounting\n
3, tom@example.com, Tom, Billing\n
```

###start()

This method allows you to run a full validation on a job you have started with the `sample` method.

``` php
use \NeverBounce\API\NB_Bulk;
NB_Bulk::app()->start(<JOB_ID>);
```

###download()

The download method lets you download a job's data once it has completed. It will return the data in the form of CSV data. You can optionally specify options in an array to download only certain items from the validated list.

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->download(<JOB_ID>);
var_dump($job);
```

####With Options

In the following example you will see the defaults used in the download options. Setting any of these to false will remove it from the downloaded data.

``` php
use \NeverBounce\API\NB_Bulk;
$options = array(
    'valids' => true
    'invalids' => true,
    'catchall' => true,
    'disposable' => true,
    'unknown' => true,
    'duplicates' => true,
    'textcodes' => true
);
$job = NB_Bulk::app()->download(<JOB_ID>, $options);
var_dump($job);
```

###delete()

**This action is not able to be undone**
Removes the job data from the NeverBounce system. Once removed the data will no longer be available on our system and can not be restored.

``` php
use \NeverBounce\API\NB_Bulk;
$job = NB_Bulk::app()->delete(<JOB_ID>);
var_dump($job);
```

Job
---
The `NB_Job` class offers helpers to making working with individual jobs easier. It abstracts the job data returned by the `status` and `list_user_jobs` endpoints. Job data is accessible using the following properties.

``` php
object(NeverBounce\API\NB_Job)#88 (15) {
  ["id"]=>int(5302)
  ["purchased"]=>bool(true)
  ["type"]=>int(0)
  ["input_location"]=>int(2)
  ["started"]=>int(1426600830)
  ["filename"]=>string(14) "list3500_a.csv"
  ["finished"]=>bool(true)
  ["state"]=>int(4)
  ["total"]=>int(3500)
  ["processed"]=>int(3500)
  ["valid"]=>int(1298)
  ["invalid"]=>int(2114)
  ["catchall"]=>int(42)
  ["disposable"]=int(0)
  ["unknown"]=>int(46)
}
```

###percentage()

This method will calculate the percentage of the desired property. It defaults to a two decimal number (000.00). If the job is not processing or completed then this will return `null`.

``` php
$job->percentage(<PROPERTY>[, <DECIMALS>]);
```

####Acceptable Properties
```
total
processed
valid
invalid
catchall
disposable
unknown
```

###total()

This method gives you a safe way to return the totals. If the job is not yet processing or completed then it will return `null` for the value. Accessing the property directly will return `0`.

``` php
$job->total(<PROPERTY>);
```

####Acceptable Properties
```
total
processed
valid
invalid
catchall
disposable
unknown
```

###download()

This is a wrapper of the `NB_Bulk::app()->download()` method. It fills out the ID for you and accepts the same options array as an argument.

``` php
$job->download([<options>]);
```

###delete()

**This action is not able to be undone**
This is a wrapper of the `NB_Bulk::app()->delete()` method. It will fill out the ID for you. Keep in mind this can not be undone.

``` php
$job->delete();
```

Exceptions
----------
This API wrapper will throw a `NB_Exception` whenever it encounters an error. It is recommended to place all requests within try/catch blocks to gracefully catch these exceptions.

An exception will be thrown in the following instances
* The cURL extension is not found
* The JSON extension is not found
* You do not supply a **secret key** to `NB_Auth::auth()`
* You do not supply an **app id** to `NB_Auth::auth()`
* You supply the wrong credentials to `NB_Auth::auth()`, a standard OAuth 2.0 error will be returned
* You did not generate an **access_token** before making a request
* You do not have sufficient credits to complete the request
* You have supplied an invalid job ID (left from v1)
* The request is malformed (verify that you are running on the correct API version)
* Your access token has expired or is invalid (Authorization failure)
* Or in the event of an internal API error

Changes
=======
#####v3.0.0
* With version 3.0 of the API, we have introduced OAuth 2.0 to replace our authentication system. For this reason we have discontinued both v1.x and v2.x APIs.
