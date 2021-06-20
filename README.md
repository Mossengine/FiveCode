<h1 align="center">Mossengine/FiveCode</h1>

<p align="center">
    <strong>A PHP library for evaluating a 5th Generation programming structure called FiveCode.</strong>
</p>

<p align="center">
    <a href="https://github.com/mossengine/fivecode"><img src="https://img.shields.io/badge/source-mossengine/fivecode-blue.svg?style=flat-square" alt="Source Code"></a>
    <a href="https://packagist.org/packages/mossengine/fivecode"><img src="https://img.shields.io/packagist/v/mossengine/fivecode.svg?style=flat-square&label=release" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/mossengine/fivecode.svg?style=flat-square&colorB=%238892BF" alt="PHP Programming Language"></a>
    <a href="https://travis-ci.org/github/Mossengine/FiveCode"><img src="https://img.shields.io/circleci/build/github/Mossengine/FiveCode/release%252F2.0.0?label=CI&logo=travis&style=flat-square" alt="Build Status"></a>
    <a href="https://codecov.io/github/mossengine/fivecode"><img src="https://img.shields.io/codecov/c/gh/mossengine/fivecode/release%252F2.0.0?label=codecov&logo=codecov&style=flat-square" alt="Codecov Code Coverage"></a>
    <a href="https://github.com/mossengine/fivecode/blob/master/LICENSE"><img src="https://img.shields.io/packagist/l/mossengine/fivecode.svg?style=flat-square&colorB=darkcyan" alt="Read License"></a>
    <a href="https://packagist.org/packages/mossengine/fivecode/stats"><img src="https://img.shields.io/packagist/dt/mossengine/fivecode.svg?style=flat-square&colorB=darkmagenta" alt="Package downloads on Packagist"></a>
    <a href="https://phpc.chat/channel/brenmoss"><img src="https://img.shields.io/badge/phpc.chat-%23brenmoss-darkslateblue?style=flat-square" alt="Chat with the maintainers"></a>
</p>


## Installation

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

## Usage

### new FiveCode()
```php
// Require the autoloader, normal composer stuff
require 'vendor/autoload.php';

// Instantiate a FiveCode class
$fiveCode = new Mossengine\FiveCode\FiveCode();

// Evaluate an array of FiveCode through the evaluate class method.
$fiveCode->evaluate([
    ['' => []],
    ['' => []],
    ['' => []],
    ['' => []]
]);
```

### FiveCode::make()
```php
// Require the autoloader, normal composer stuff
require 'vendor/autoload.php';

// Use the static make method and immediately evaluate an array of instructions
Mossengine\FiveCode\FiveCode::make()
    ->evaluate([
        ['' => []],
        ['' => []],
        ['' => []],
        ['' => []]
    ]);
```

## Documentation
Read the <a href="/docs/index.md">docs</a> for more details on FiveCode language structure or to create your own parsers/functions to provide more capabilities
