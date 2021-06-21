<h1 align="center">FiveCode: Documentation</h1>

## Basics

### Structures

#### arrays
FiveCode uses non-associative arrays to define instructions of other instructional arrays, parsable objects and literal values, the order of these within the array define their order of execution.
```php
[
    [], // another instructional array
    {}, // an associative array ( object )
    0 // a literal value
]
```
The above would result in the evaluation processing another array of potential instructions, then a parsing object, and finally a literal value.

#### objects
FiveCode uses associative arrays ( objects ) to identify and parse them for extra instructional information that gets provided to the identified parser, from there the parser will do specifically what it is programmed to do with the information it is provided. See parsers for more information.
```php
[
    // The first and what should be only key in the associative array is used to locate the correct parer for processing this instruction
    '...' => [
        ... // The value in this associative array should always be an array that contains potentially more instructions or values at key zero indexed positions relative to the type of parser
    ]
]
```
The above would result in the associative array being passed through a parser that has registered itself for processing the specific first position key in that associative array, anything that happens within the parser can sometimes be specific to the parser.

#### literals
FiveCode will use treat anything that is not an array ( associative and non-associative ) as just a literal value that does not get parse and instead returned as defined.
```php
[
    0,
    0.005,
    'text',
    true
]
```

### Returns
It is intentioned that all parsers have logic within them to set a return value, should the parse have no reason to have a return then it will return the previous instructions return value ( pass through the return of the previous instruction ).
The return value of all parsed instructions is set into a variable called `_return` and can be accessed using the `get` parser through an associative array instruction ( object ). see [variables](/variables.md) for more information

### Examples

#### set a variable using a literal and get it back
```php
[
    ['set' => [
        'myVariableKey',
        'myVariableValue'
    ]],
    ['get' => [
        'myVariableKey',
        'aDefaultValueWhenNotFound'
    ]]
]
```
The above should result in the final `_return` value being `myVariableValue`.

#### call a function
This only works if the function is enabled through the settings, see [settings](/settings.md)) for more information.
```php
[
    ['call' => [
        'array_sum',
        ['array' => [
            1,
            2,
            3
        ]]
    ]]
]
```
This uses another parser type called `array` which allows you to define a literal array where normally doing so would not work due to any array in the FiveCode structure is by default considered to be an array of instructions and execute in order without returning all their results as one array, to get around this we have the `array` parser that does this behaviour but intentionally returns back an array of all the results within... so you can still put parsable objects within as it's values and even more instruction arrays / literals.
```php
[
    ['call' => [
        'array_sum',
        ['array' => [
            [
                ['get' => [
                    'myVariableKey'
                ]]
            ],
            2,
            3
        ]]
    ]]
]
```

#### perform logic based on a condition
```php
[
    ['if' => [
        ['===' => [1, 1]],
        'yes',
        'no'
    ]]
]
```
This will use the `if` parser, the first argument within the parsed information is the condition, the second and then first is the logic to execute only when true or false respectively. We should have a `_return` variable set as `yes` based on this condition resulting in `true`

#### iterate over some data
```php
[
    ['each' => [
        ['array' => [
            'five',
            'six',
            'seven',
            'eight'
        ]],
        [
            ['set' => [
                'key1.index',
                ['get' => [
                    '_iterator.each.index'
                ]]
            ]],
            ['set' => [
                'key1.item',
                ['get' => [
                    '_iterator.each.item'
                ]]
            ]]
        ]
    ]]
]
```
This calls on the `each` parser which is basically `foreach` logic, the array value that comes from the parsed `array` first argument is the array of items to iterate, the second argument is the logic to be evaluated on each iteration. You will notice that the logic is accessing `_iterator.*` variable keys to gain access to the current `index` and `item` of the iteration, this is similar behaviour for other iterators. see [Iterators](/iterators.md) for more information.