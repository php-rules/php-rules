PHP Rules
=========

[![Build Status](https://travis-ci.org/DerManoMann/phprules.png)](https://travis-ci.org/DerManoMann/phprules)

PHPRules is a PHP 5.3 fork of the [php-rules][1] project.



## Getting started
"PHP Rules is a rule engine that models formal propositional logic. It allows you to 
separate conditional logic from source code and database triggers in a reusable package, 
where explicit rules can be independently defined and managed."

[...more][2]



## New Features

  * simplified rule syntax using `BOOL`/`VAR` to indicate propositions/variables
  * refactored rule/context loading that should make it easier to integrate into different storage systems
  * `IN` operator to evaluate if a value exists in a list
  * support to dynamically add operators
  * `CompositeRule` class to allow to pragmatically create nested rules



## Installation

The recommended way to install phprules is [through
composer](http://getcomposer.org). Just create a `composer.json` file and
run the `php composer.phar install` command to install it:

    {
        "require": {
            "radebatz/phprules": "1.0.*@dev"
        }
    }

Alternatively, you can download the [`phprules.zip`][3] file and extract it.



## License

PHPRules is licensed under both the MIT and GPL 2.0 licenses.



[1]: http://www.swindle.net/php-rules/
[2]: http://www.swindle.net/php-rules/tutorials/getting-started
[3]: https://github.com/DerManoMann/phprules/archive/master.zip
