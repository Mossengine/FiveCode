<h1 align="center">FiveCode: Documentation</h1>

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