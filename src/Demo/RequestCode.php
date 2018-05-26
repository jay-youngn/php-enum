<?php

namespace PHPEnum\Demo;

use PHPEnum\Enum;

/**
 * custom global request code
 */
class RequestCode extends Enum
{
    /**
     * request success
     * @var integer
     */
    const SUCCESS = 0;

    /**
     * request failure
     * @var integer
     */
    const ERROR = 1;

    /**
     * the display value for view
     */
    const __DICT = [
        self::SUCCESS => 'request success',
        self::ERROR => 'request failure',
    ];

}
