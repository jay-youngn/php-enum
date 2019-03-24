<?php

namespace PHPEnum\Demo;

use PHPEnum\Enum;

/**
 * Custom global request code.
 */
class RequestCode extends Enum
{
    /**
     * Request success.
     *
     * @var int
     */
    const SUCCESS = 0;

    /**
     * Request failure.
     *
     * @var int
     */
    const ERROR = 1;

    /**
     * The display value for view.
     */
    const __DICT = [
        self::SUCCESS => 'request success',
        self::ERROR   => 'request failure',
    ];
}
