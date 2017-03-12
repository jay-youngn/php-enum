<?php

namespace PHPEnum;

use ReflectionClass;
use UnexpectedValueException;

/**
 * basic class
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
     * 保存子类实例
     * @var array
     */
    private static $__instance = [];

    public function __construct()
    {
        $ref = new ReflectionClass($this->_getClass());
        $constants = $ref->getConstants();

        unset($constants['__MAP']);

        // get const name
        $nameMap = array_flip($constants);

        // const->value
        $this->consts = $constants;
        // value->const
        $this->constsRef = $nameMap;
        // const->text
        foreach ($nameMap as $k => $v) {
            $this->keyMap[$v] = static::__MAP[$k];
        }
    }

    /**
     * 判断是否有此名称的枚举
     *
     * @param  string $constName
     * @return boolean
     */
    protected function _hasConst($constName)
    {
        return in_array($constName, $this->constsRef, TRUE);
    }

    /**
     * 判断枚举中是否有这个值
     *
     * @param  mixed $value
     * @param  boolean $strict
     * @return boolean
     */
    protected function _hasValue($value, $strict = FALSE)
    {
        return in_array($value, $this->consts, $strict);
    }

    /**
     * 名称转换值
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
     * 值转名称
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
     * 枚举名称转中文
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
     * 值转中文
     *
     * @param  mixed $value
     * @return string
     */
    protected function _transValue($value)
    {
        if ($this->_hasValue($value)) {
            return static::__MAP[$value];
        }

        return $value;
    }

    /** getter */

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
        return static::__MAP;
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
     * 使用静态方法创建并保存类对象的实例
     *
     * @return object
     */
    public static function getInstance()
    {
        if (empty(self::$__instance[static::class]) || !self::$__instance[static::class] instanceof static) {
            self::$__instance[static::class] = new static;
        }

        return self::$__instance[static::class];
    }

    /**
     * __callStatic
     * 以静态方式调用protected方法
     * 如:
     * 1. xxxEnum::hasConst('CONST_NAME')
     *   // 实际上调用了 $xxxEnum->_hasConst('CONST_NAME')
     * 2. xxxEnum::getMap()
     *   // 实际上调用了 $xxxEnum->_getMap()
     *
     * @param  string $func
     * @param  array $arguments
     * @return mixed
     */
    public static function __callStatic($func, $arguments)
    {
        return call_user_func_array([self::getInstance(), '_' . $func], $arguments);
    }
}
