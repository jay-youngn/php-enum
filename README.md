# php-enum
用PHP实现枚举

- This class gives the ability to emulate and get enumeration data natively in PHP.
- You can convert enum values easily.
- requires php>=5.6.0, because array constant are used.

## Get started

### Install
```shell
composer require ginnerpeace/php-enum
```

### Example
Create a class for an enum data, like this:
```php
<?php

namespace PHPEnum\Demo;

use PHPEnum\Enum;

/**
 * custom global request code
 */
class RequestCode extends Enum {

    /**
     * request success
     * @var integer
     */
    CONST SUCCESS = 0;

    /**
     * request failure
     * @var integer
     */
    CONST ERROR = 1;

    CONST __DICT = [
        self::SUCCESS => 'request success',
        self::ERROR => 'request failure',
    ];

}

/** Instructions */

RequestCode::SUCCESS;                 // 0
RequestCode::ERROR;                   // 1

RequestCode::hasConst('SUCCESS');     // TRUE
RequestCode::hasConst('TEST');        // FALSE

RequestCode::hasValue(1);             // TRUE
RequestCode::hasValue('1');           // TRUE
// strict mode
RequestCode::hasValue('1', TRUE);     // FALSE
RequestCode::hasValue(9);             // FALSE

RequestCode::constToValue('SUCCESS'); // 0
RequestCode::constToValue('TEST');    // throw UnexpectedValueException

RequestCode::valueToConst(1);         // 'ERROR'
RequestCode::valueToConst(9);         // throw UnexpectedValueException

RequestCode::transConst('ERROR');     // 'request failure'
RequestCode::transConst('TEST');      // 'TEST'

RequestCode::transValue(0);           // 'request success'
RequestCode::transValue(9);           // 9

RequestCode::getConsts();
return:
[
    0 => 'SUCCESS',
    1 => 'ERROR',
]

RequestCode::getConstsRef();
return:
[
    'SUCCESS' => 0,
    'ERROR' => 1,
]

RequestCode::getMap();
return:
[
    0 => 'request success',
    1 => 'request failure',
]

RequestCode::getKeyMap();
return:
[
    'SUCCESS' => 'request success',
    'ERROR' => 'request failure',
]

```
