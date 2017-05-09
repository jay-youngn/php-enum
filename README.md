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

RequestCode::hasName('SUCCESS');     // TRUE
RequestCode::hasName('TEST');        // FALSE

// 默认 strict mode
RequestCode::hasValue(1);             // TRUE
RequestCode::hasValue('1');           // FALSE

// 传参不使用 strict mode
RequestCode::hasValue('1', FALSE);    // TRUE
RequestCode::hasValue(9);             // FALSE

RequestCode::nameToValue('SUCCESS'); // 0
RequestCode::nameToValue('TEST');    // throw UnexpectedValueException

RequestCode::valueToName(1);         // 'ERROR'
RequestCode::valueToName(9);         // throw UnexpectedValueException

RequestCode::transName('ERROR');     // 'request failure'
RequestCode::transName('TEST');      // 'TEST'

RequestCode::transValue(0);           // 'request success'
RequestCode::transValue(9);           // 9

RequestCode::getMap();
return:
[
    'SUCCESS' => 0,
    'ERROR' => 1,
]

RequestCode::getNameMap();
return:
[
    0 => 'SUCCESS',
    1 => 'ERROR',
]

RequestCode::getDict();
return:
[
    0 => 'request success',
    1 => 'request failure',
]

RequestCode::getNameDict();
return:
[
    'SUCCESS' => 'request success',
    'ERROR' => 'request failure',
]

```
