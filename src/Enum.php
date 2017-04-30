<?php

namespace PHPEnum;

use ReflectionClass;
use UnexpectedValueException;

/**
 * php enum basic class
 *
 * @author gjy <ginnerpeace@live.com>
 * @link https://github.com/ginnerpeace/php-enum
 */
abstract class Enum
{
    /**
     * value => const name
     * @var array
     */
    protected $consts = [];

    /**
     * const name => value
     * @var array
     */
    protected $constsRef = [];

    /**
     * const name => display value
     * @var array
     */
    protected $keyMap = [];

    /**
     * Save instance
     * @var array
     */
    private static $__instance = [];

    /**
     * Create const list for current class.
     */
    public function __construct()
    {
        $ref = new ReflectionClass($this->_getClass());

        // const->value
        $this->consts = $ref->getConstants();

        // value->const
        $this->constsRef = array_flip($this->consts);

        // const->text
        foreach ($this->constsRef as $k => $v) {
            $this->keyMap[$v] = static::__DICT[$k];
        }
    }

    /**
     * Checks if the given constant name exists in the enum.
     *
     * @param  string $constName
     * @return boolean
     */
    protected function _hasConst($constName)
    {
        return in_array($constName, $this->constsRef, true);
    }

    /**
     * Checks if the given value exists in the enum.
     *
     * @param  mixed $value
     * @param  boolean $strict
     * @return boolean
     */
    protected function _hasValue($value, $strict = false)
    {
        return in_array($value, $this->consts, $strict);
    }

    /**
     * Translate the given constant name to the value.
     *
     * @param  string $constName
     * @throws UnexpectedValueException
     * @return mixed
     */
    protected function _constToValue($constName)
    {
        if (!$this->_hasConst($constName)) {
            throw new UnexpectedValueException("Const {$constName} is not in Enum" . $this->_getClass());
        }

        return $this->consts[$constName];
    }

    /**
     * Translate the given value to the constant name.
     *
     * @param  mixed $value
     * @throws UnexpectedValueException
     * @return string
     */
    protected function _valueToConst($value)
    {
        if (!$this->_hasValue($value)) {
            throw new UnexpectedValueException("Value {$value} is not in Enum" . $this->_getClass());
        }

        return $this->constsRef[$value];
    }

    /**
     * Translate the given constant name to the display value.
     *
     * @param  string $constName
     * @return string
     */
    protected function _transConst($constName)
    {
        if ($this->_hasConst($constName)) {
            return $this->keyMap[$constName];
        }

        return $constName;
    }

    /**
     * Translate the given value to the display value.
     *
     * @param  mixed $value
     * @return string
     */
    protected function _transValue($value)
    {
        if ($this->_hasValue($value)) {
            return static::__DICT[$value];
        }

        return $value;
    }

    /** getters */

    protected function _getConsts()
    {
        return $this->consts;
    }

    protected function _getConstsRef()
    {
        return $this->constsRef;
    }

    protected function _getMap()
    {
        return static::__DICT;
    }

    protected function _getKeyMap()
    {
        return $this->keyMap;
    }

    protected function _getClass()
    {
        return static::class;
    }

    /**
     * Get current class instance from a static variable.
     *
     * @return PHPEnum\Enum
     */
    public static function getInstance()
    {
        if (empty(self::$__instance[static::class]) || !self::$__instance[static::class] instanceof static) {
            self::$__instance[static::class] = new static;
        }

        return self::$__instance[static::class];
    }

    /**
     *
     * Call the protected method statically.
     *
     * example:
     * 1. xxxEnum::hasConst('CONST_NAME')
     * // Actually called: $xxxEnum->_hasConst('CONST_NAME')
     * 2. xxxEnum::getMap()
     * // Actually called: $xxxEnum->_getMap()
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return call_user_func_array([self::getInstance(), '_' . $method], $arguments);
    }
}
