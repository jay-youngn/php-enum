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

    /**
     * the display value for view
     */
    CONST __DICT = [
        self::SUCCESS => 'request success',
        self::ERROR => 'request failure',
    ];

}
