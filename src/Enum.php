<?php

namespace PHPEnum;

use ReflectionClass;
use UnexpectedValueException;

/**
 * basic class for php enum.
 *          _
 *     __ _(_)_ __  _ __   ___ _ __ _ __   ___  __ _  ___ ___
 *    / _` | | '_ \| '_ \ / _ \ '__| '_ \ / _ \/ _` |/ __/ _ \
 *   | (_| | | | | | | | |  __/ |  | |_) |  __/ (_| | (_|  __/
 *    \__, |_|_| |_|_| |_|\___|_|  | .__/ \___|\__,_|\___\___|
 *    |___/                        |_|
 *
 * @author gjy <ginnerpeace@live.com>
 * @link https://github.com/ginnerpeace/php-enum
 */
abstract class Enum
{
    /**
     * Name of current Enum data.
     * @var string
     */
    protected $name;

    /**
     * Value of current Enum data.
     * @var integer|string
     */
    protected $value;

    /**
     * Map of Enum values.
     *
     * const name => value
     * @var array
     */
    protected $valueMap = [];

    /**
     * Map of Enum names.
     *
     * value => const name
     * @var array
     */
    protected $nameMap = [];

    /**
     * Constant name dictionary.
     *
     * const name => display value
     * @var array
     */
    protected $nameDict = [];

    /**
     * Save instance
     * @var array
     */
    private static $__instance = [];

    /**
     * Create const list for current class.
     */
    public function __construct($value = null)
    {
        // const name -> value
        $this->valueMap = (new ReflectionClass($this->_getClass()))->getConstants();

        unset($this->valueMap['__DICT']);

        // value -> const name
        $this->nameMap = array_flip($this->valueMap);

        if (! is_null($value)) {
            $this->name = $this->_valueToName($value);
            $this->value = $value;
        }

        // constname -> display text
        foreach ($this->nameMap as $k => $v) {
            $this->nameDict[$v] = static::__DICT[$k];
        }
    }

    /**
     * Checks if the given constant name exists in the enum.
     *
     * @param  string $constName
     * @return boolean
     */
    protected function _hasName($constName)
    {
        return in_array($constName, $this->nameMap, true);
    }

    /**
     * Checks if the given value exists in the enum.
     *
     * @param  mixed $value
     * @param  boolean $strict
     * @return boolean
     */
    protected function _hasValue($value, $strict = true)
    {
        return in_array($value, $this->valueMap, $strict);
    }

    /**
     * Translate the given constant name to the value.
     *
     * @param  string $constName
     * @throws UnexpectedValueException
     * @return mixed
     */
    protected function _nameToValue($constName)
    {
        if (! $this->_hasName($constName)) {
            throw new UnexpectedValueException("Const {$constName} is not in Enum " . $this->_getClass());
        }

        return $this->valueMap[$constName];
    }

    /**
     * Translate the given value to the constant name.
     *
     * @param  mixed $value
     * @throws UnexpectedValueException
     * @return string
     */
    protected function _valueToName($value)
    {
        if (! $this->_hasValue($value)) {
            throw new UnexpectedValueException("Value {$value} is not in Enum " . $this->_getClass());
        }

        return $this->nameMap[$value];
    }

    /**
     * Translate the given constant name to the display value.
     *
     * @param  string $constName
     * @return string
     */
    protected function _transName($constName)
    {
        if ($this->_hasName($constName)) {
            return $this->nameDict[$constName];
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

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    protected function _getMap()
    {
        return $this->valueMap;
    }

    protected function _getNameMap()
    {
        return $this->nameMap;
    }

    protected function _getDict()
    {
        return static::__DICT;
    }

    protected function _getNameDict()
    {
        return $this->nameDict;
    }

    protected function _getClass()
    {
        return static::class;
    }

    /**
     * create new instance
     *
     * @return void
     */
    private static function createInstance()
    {
        self::$__instance[static::class] = new static;
    }

    /**
     * Get current class instance from a static variable.
     *
     * @return PHPEnum\Enum
     */
    public static function getInstance()
    {
        if (empty(self::$__instance[static::class])) {
            self::createInstance();
        }

        return self::$__instance[static::class];
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     *
     * Call the protected method statically.
     *
     * example:
     * 1. xxxEnum::hasName('CONST_NAME')
     * // Actually called: $xxxEnum->_hasName('CONST_NAME')
     * 2. xxxEnum::getDict()
     * // Actually called: $xxxEnum->_getDict()
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return call_user_func_array([self::getInstance(), '_' . $method], $arguments);
    }

    /**
     *
     * Call the protected method in new instance.
     *
     * example:
     * 1. (new Enum(1))->hasName('CONST_NAME')
     * // Actually called: $xxxEnum->_hasName('CONST_NAME')
     * 2. (new Enum(1))->getDict()
     * // Actually called: $xxxEnum->_getDict()
     * 3. (new Enum(1))->getName()
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this, '_' . $method], $arguments);
    }
}
