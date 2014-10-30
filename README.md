Slim HMAC Authentication Middleware
===================================

***Slim HMAC Authentication Middleware*** is a Slim middleware library that authenticates requests with HMAC.

[![Build Status](https://travis-ci.org/ilanco/slim-auth-hmac.svg?branch=master)](https://travis-ci.org/ilanco/slim-auth-hmac)

Requirements
------------

* PHP 5.4
* Slim Framework 2

Installation
------------

The only (currently) supported method of installation is via
[Composer](http://getcomposer.org).

Create a `composer.json` file in the root of your project:

``` json
{
    "require": {
        "ilanco/slim-auth-hmac": "dev-master"
    }
}
```

And then run: `composer install`

Add the autoloader to your project:

``` php
<?php

require_once 'vendor/autoload.php'
```

You're now ready to begin using Slim HMAC Authentication Middleware.

Documentation
-------------

Documentation is provided in the code.

Development
-----------

Slim Auth HMAC is hosted by GitHub. You can download the code and contribute
here: [ilanco/slim-auth-hmac][].

Bug Reporting
-------------

Please open a Github issue if you find a bug.

Licensing
---------

Please see the file called LICENSE.

[ilanco/slim-auth-hmac]: https://github.com/ilanco/slim-auth-hmac
"ilanco/slim-auth-hmac"
