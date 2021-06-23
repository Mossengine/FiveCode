<h1 align="center">Mossengine/FiveCode</h1>

<p align="center">
    <strong>A PHP library for evaluating a 5th Generation programming structure called FiveCode.</strong>
</p>

<p align="center">
    <a href="https://github.com/mossengine/fivecode"><img src="https://badgen.net/packagist/name/Mossengine/FiveCode" alt="Source Code"></a>
    <a href="https://packagist.org/packages/mossengine/fivecode"><img src="https://badgen.net/packagist/v/Mossengine/FiveCode" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://badgen.net/packagist/php/Mossengine/FiveCode" alt="PHP Programming Language"></a>
    <img src="https://badgen.net/circleci/github/Mossengine/FiveCode/master?icon=circleci" alt="Build Status">
    <a href="https://codecov.io/github/mossengine/fivecode"><img src="https://badgen.net/codecov/c/github/Mossengine/FiveCode/master?icon=codecov" alt="Codecov Code Coverage"></a>
    <a href="https://github.com/mossengine/fivecode/blob/master/LICENSE"><img src="https://badgen.net/packagist/license/Mossengine/FiveCode" alt="Read License"></a>
    <a href="https://packagist.org/packages/mossengine/fivecode/stats"><img src="https://badgen.net/packagist/dt/Mossengine/FiveCode" alt="Package downloads on Packagist"></a>
    <a href="https://phpc.chat/channel/brenmoss"><img src="https://badgen.net/badge/phpc.chat/brenmoss/blue" alt="Chat with the maintainers"></a>
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
