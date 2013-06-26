# PHP Rules #

[![Build Status](https://travis-ci.org/DerManoMann/phprules.png)](https://travis-ci.org/DerManoMann/phprules)

PHPRules  is a PHP 5.3 fork of the [php-rules](http://www.swindle.net/php-rules/) project.

## Getting started ##
"PHP Rules is a rule engine that models formal propositional logic. It allows you to 
separate conditional logic from source code and database triggers in a reusable package, 
where explicit rules can be independently defined and managed."

[...more](http://www.swindle.net/php-rules/tutorials/getting-started))

## New Features ##
* simplified rule syntax using `BOOL`/`VAR` to indicate propositions/variables
* refactored rule/context loading that should make it easier to integrate into different storage systems
* `IN` operator to evaluate if a value exists in a list
* support to dynamically add operators
* `CompositeRule` class to allow to pragmatically create nested rules
