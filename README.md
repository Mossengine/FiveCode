# FiveCode

[![Latest Stable Version](https://poser.pugx.org/mossengine/fivecode/v/stable)](https://packagist.org/packages/mossengine/fivecode)
[![Latest Unstable Version](https://poser.pugx.org/mossengine/fivecode/v/unstable)](https://packagist.org/packages/mossengine/fivecode)
[![License](https://poser.pugx.org/mossengine/fivecode/license)](https://packagist.org/packages/mossengine/fivecode)
[![composer.lock](https://poser.pugx.org/mossengine/fivecode/composerlock)](https://packagist.org/packages/mossengine/fivecode)

[![Build Status](https://travis-ci.org/Mossengine/FiveCode.svg?branch=master)](https://travis-ci.org/Mossengine/FiveCode)
[![codecov](https://codecov.io/gh/Mossengine/FiveCode/branch/master/graph/badge.svg)](https://codecov.io/gh/Mossengine/FiveCode)

[![Total Downloads](https://poser.pugx.org/mossengine/fivecode/downloads)](https://packagist.org/packages/mossengine/fivecode)
[![Monthly Downloads](https://poser.pugx.org/mossengine/fivecode/d/monthly)](https://packagist.org/packages/mossengine/fivecode)
[![Daily Downloads](https://poser.pugx.org/mossengine/fivecode/d/daily)](https://packagist.org/packages/mossengine/fivecode)

PHP Class to enable fifth generation instructional code driven by programmatic instructions to execute under a controlled logic in the backend. 


## Functions
### __constructor()
```php
<?php
// Currently no constructor but one will be here soon to support limits and settings.
```

## Installation

### With Composer

```
$ composer require mossengine/fivecode
```

```json
{
    "require": {
        "mossengine/fivecode": "~1.0.0"
    }
}
```

```php
<?php
// Require the autoloader, normal composer stuff
require 'vendor/autoload.php';

// Instantiate a FiveCode class
$classFiveCode = new Mossengine\FiveCode\FiveCode1;

// Execute an array of JCode directly into the class
$classFiveCode->execute([
    'variables' => [
        'boolResult' => false
    ],
    'instructions' => [
        [
            'type' => 'variables',
            'variables' => [
                [
                    'variable' => 'boolResult',
                    'type' => 'value',
                    'value' => true
                ]
            ]
        ]
    ]
]);
```


### Without Composer

Why are you not using [composer](http://getcomposer.org/)? Download [Jcode.php](https://github.com/Mossengine/FiveCode/blob/master/src/FiveCode.php) from the repo and save the file into your project path somewhere. This project does not support composerless environments.


### String JSON

Instead of PHP Associative Array you can also just send in JSON stringify structure and to save you the time we decode it for you.

```php
$classFiveCode->executeJson('{"variables":{"boolResult":false},"instructions":[{"type":"variables","variables":[{"variable":"boolResult","type":"value","value":true}]}]}');
```


### Getting back the results

Simply call on the variable function and define a variable key to get a specific key value back or no defined key and you will get all the variables back

```php
$mixedValue = $classFiveCode->variable('boolResult');

$arrayVariables = $classFiveCode->variable();
```


### Modify variables

You can also modify variable values or define new variables by using the exact same variable function but provide a second parameter for the value you wish to assign to the variable name

```php
$classFiveCode->variable('boolResult', false);

$classFiveCode->variable('stringNewVariable', 'Hello Wolrd is cliché!');

$classFiveCode->variable(null, [
    'new' => 'array'
]);
```