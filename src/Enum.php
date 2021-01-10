<?php

namespace PHPEnum;

use BadMethodCallException;
use ReflectionClass;
use UnexpectedValueException;

/**
 * Basic class for php enum.
 *
 * @link https://github.com/ginnerpeace/php-enum
 */
abstract class Enum
{
    /**
     * Map for all display values.
     *
     * @var array
     */
    const __DICT = array();

    /**
     * Name of current Enum data.
     *
     * @var string
     */
    protected $name;

    /**
     * Value of current Enum data.
     *
     * @var int|string
     */
    protected $value;

    /**
     * Map of Enum values.
     *
     * const name => value
     *
     * @var array
     */
    protected static $valueMap = array();

    /**
     * Map of Enum names.
     *
     * value => const name
     *
     * @var array
     */
    protected static $nameMap = array();

    /**
     * Constant name dictionary.
     *
     * const name => display value
     *
     * @var array
     */
    protected static $nameDict = array();

    /**
     * The array of booted enums.
     *
     * @var array
     */
    protected static $booted = array();

    /**
     * Save instance.
     *
     * @var array
     */
    private static $__instance = array();


    /**
     * Create const list for current class.
     *
     * @param mixed|null $value
     */
    public function __construct($value = null)
    {
        static:: bootIfNotBooted();

        if (! is_null($value)) {
            $this->name = $this->_valueToName($value);
            $this->value = $value;
        }
    }

    /**
     * Get name of Enum instance.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get value of Enum instance.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * Check if the enum needs to be booted and if so, do it.
     *
     * @return void
     */
    protected static function bootIfNotBooted()
    {
        if (isset(static::$booted[static::class])) {
            return;
        }

        static::$booted[static::class] = true;

        // const name -> value
        static::$valueMap[static::class] = (new ReflectionClass(static::class))->getConstants();

        unset(static::$valueMap[static::class]['__DICT']);

        // value -> const name
        static::$nameMap[static::class] = array_flip(static::$valueMap[static::class]);

        // constname -> display text
        foreach (static::$nameMap[static::class] as $k => $v) {
            static::$nameDict[static::class][$v] = static::__DICT[$k];
        }
    }

    /**
     * Checks if the given constant name exists in the enum.
     *
     * @param  string $constName
     * @return bool
     */
    protected function _hasName($constName)
    {
        return isset(static::$valueMap[static::class][$constName]);
    }

    /**
     * Checks if the given value exists in the enum.
     *
     * @param  mixed $value
     * @param  bool  $strict
     * @return bool
     */
    protected function _hasValue($value, $strict = true)
    {
        return in_array($value, static::$valueMap[static::class], $strict);
    }

    /**
     * Translate the given constant name to the value.
     *
     * @param  string $constName
     * @return mixed
     *
     * @throws UnexpectedValueException
     */
    protected function _nameToValue($constName)
    {
        if (! $this->_hasName($constName)) {
            throw new UnexpectedValueException("Const {$constName} is not in Enum " . static::class);
        }

        return static::$valueMap[static::class][$constName];
    }

    /**
     * Translate the given value to the constant name.
     *
     * @param  mixed $value
     * @return string
     *
     * @throws UnexpectedValueException
     */
    protected function _valueToName($value)
    {
        if (! $this->_hasValue($value)) {
            throw new UnexpectedValueException("Value {$value} is not in Enum " . static::class);
        }

        return static::$nameMap[static::class][$value];
    }

    /**
     * Translate the given constant name to the display value.
     *
     * @param  string $constName
     * @return string
     */
    protected function _transName($constName)
    {
        return isset(static::$nameDict[static::class][$constName]) ? static::$nameDict[static::class][$constName] : $constName;
    }

    /**
     * Translate the given value to the display value.
     *
     * @param  mixed $value
     * @return string
     */
    protected function _transValue($value)
    {
        return isset(static::__DICT[$value]) ? static::__DICT[$value] : $value;
    }

    ///////////////////////////////////////////////////////////////////
    ///////////////////////// SPECIFIC GETTERS ////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Get map for all values, key by const name.
     *
     * @return array
     */
    protected function _getMap()
    {
        return static::$valueMap[static::class];
    }

    /**
     * Get map for all names, key by value.
     *
     * @return array
     */
    protected function _getNameMap()
    {
        return static::$nameMap[static::class];
    }

    /**
     * Get map for all display values, key by value.
     *
     * @return array
     */
    protected function _getDict()
    {
        return static::__DICT;
    }

    /**
     * Get map for all display values, key by const name.
     *
     * @return array
     */
    protected function _getNameDict()
    {
        return static::$nameDict[static::class];
    }

    /**
     * Create new instance for current class.
     *
     * @return static
     */
    private static function createEmptyInstance()
    {
        return new static(null);
    }

    /**
     * Get current class instance from the static attribute.
     *
     * @return PHPEnum\Enum
     */
    public static function getInstance()
    {
        if (empty(self::$__instance[static::class])) {
            self::$__instance[static::class] = self::createEmptyInstance();
        }

        return self::$__instance[static::class];
    }

    /**
     * Call the helper method which starts with underscore.
     *
     * example:
     *
     * 1. (new Enum(1))->hasName('CONST_NAME')
     *    Actually called: $xxxEnum->_hasName('CONST_NAME')
     *
     * 2. (new Enum(1))->getDict()
     *    Actually called: $xxxEnum->_getDict()
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (! method_exists($this, $invoking = '_' . $method)) {
            throw new BadMethodCallException('Method ' . $invoking . ' does not exists');
        }

        switch (count($arguments)) {
            case 0:
                return $this->$invoking();
            case 1:
                return $this->$invoking($arguments[0]);
            case 2:
                return $this->$invoking($arguments[0], $arguments[1]);
            case 3:
                return $this->$invoking($arguments[0], $arguments[1], $arguments[2]);
            case 4:
                return $this->$invoking($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
            default:
                return call_user_func_array([$this, $invoking], $arguments);
        }
    }

    /**
     * Call some helper method statically.
     * Overloading to __call
     *
     * example:
     *
     * 1. xxxEnum::hasName('CONST_NAME')
     *    Actually called: $xxxEnum->_hasName('CONST_NAME')
     *
     * 2. xxxEnum::getDict()
     *    Actually called: $xxxEnum->_getDict()
     *
     * @see __call()
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = self::getInstance();

        switch (count($arguments)) {
            case 0:
                return $instance->$method();
            case 1:
                return $instance->$method($arguments[0]);
            case 2:
                return $instance->$method($arguments[0], $arguments[1]);
            case 3:
                return $instance->$method($arguments[0], $arguments[1], $arguments[2]);
            case 4:
                return $instance->$method($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
            default:
                return call_user_func_array([$instance, $method], $arguments);
        }
    }
}
