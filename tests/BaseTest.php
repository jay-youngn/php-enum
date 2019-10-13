<?php

use PHPEnum\Enum;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public function testInstantiable()
    {
        $emptyEnum = new RequestCode;
        $this->assertInstanceOf(Enum::class, $emptyEnum);

        $this->assertNull($emptyEnum->getName());
        $this->assertNull($emptyEnum->getValue());

        $this->assertTrue(method_exists($emptyEnum, 'getName'));
        $this->assertTrue(method_exists($emptyEnum, 'getValue'));

        $this->assertTrue(method_exists($emptyEnum, '_getMap'));
        $this->assertTrue(method_exists($emptyEnum, '_getNameMap'));
        $this->assertTrue(method_exists($emptyEnum, '_getDict'));
        $this->assertTrue(method_exists($emptyEnum, '_getNameDict'));

        $this->assertTrue(method_exists($emptyEnum, '_hasName'));
        $this->assertTrue(method_exists($emptyEnum, '_hasValue'));
        $this->assertTrue(method_exists($emptyEnum, '_getNameDict'));
        $this->assertTrue(method_exists($emptyEnum, '_getNameDict'));

        $this->assertTrue(method_exists($emptyEnum, '_getNameDict'));
        $this->assertTrue(method_exists($emptyEnum, '_getNameDict'));

        $this->assertTrue(method_exists($emptyEnum, '__toString'));

        // 实例化带值的enum
        $enum = new RequestCode(RequestCode::SUCCESS);

        $this->assertObjectHasAttribute('name', $enum);
        $this->assertObjectHasAttribute('value', $enum);

        $this->assertInternalType('string', $enum->getName());
        $this->assertInternalType('int', $enum->getValue());

        $this->assertInternalType('string', $enum->__toString());
    }

    public function testStaticable()
    {
        /////////////////////
        // Test basic use. //
        /////////////////////

        $this->assertInternalType('int', RequestCode::SUCCESS);
        $this->assertInternalType('int', RequestCode::ERROR);

        $this->assertInternalType('array', RequestCode::getMap());
        $this->assertInternalType('array', RequestCode::getNameMap());
        $this->assertInternalType('array', RequestCode::getDict());
        $this->assertInternalType('array', RequestCode::getNameDict());

        $nameSuccess = RequestCode::valueToName(RequestCode::SUCCESS);
        $nameError = RequestCode::valueToName(RequestCode::ERROR);

        $this->assertInternalType('string', $nameSuccess);
        $this->assertInternalType('string', $nameError);

        $this->assertTrue(RequestCode::hasName($nameSuccess));
        $this->assertTrue(RequestCode::hasName($nameError));

        $this->assertTrue(RequestCode::SUCCESS === RequestCode::nameToValue($nameSuccess));
        $this->assertTrue(RequestCode::ERROR === RequestCode::nameToValue($nameError));

        /////////////////////////////////
        // Test determination methods. //
        /////////////////////////////////

        $this->assertTrue(RequestCode::hasValue(RequestCode::SUCCESS));
        $this->assertTrue(RequestCode::hasValue(RequestCode::ERROR));

        $this->assertTrue(RequestCode::hasValue(strval(RequestCode::SUCCESS), false));
        $this->assertTrue(RequestCode::hasValue(strval(RequestCode::ERROR), false));

        $this->assertFalse(RequestCode::hasValue(strval(RequestCode::SUCCESS)));
        $this->assertFalse(RequestCode::hasValue(strval(RequestCode::ERROR)));
        $this->assertFalse(RequestCode::hasValue(9999999));

        $this->assertFalse(RequestCode::hasName('some impossible name.'));

        //////////////////
        // Test getter. //
        //////////////////

        $this->assertArrayHasKey(RequestCode::SUCCESS, RequestCode::getNameMap());
        $this->assertArrayHasKey(RequestCode::ERROR, RequestCode::getNameMap());
        $this->assertArrayHasKey(RequestCode::SUCCESS, RequestCode::getDict());
        $this->assertArrayHasKey(RequestCode::ERROR, RequestCode::getDict());


        $this->assertArrayHasKey($nameSuccess, RequestCode::getMap());
        $this->assertArrayHasKey($nameError, RequestCode::getMap());
        $this->assertArrayHasKey($nameSuccess, RequestCode::getNameDict());
        $this->assertArrayHasKey($nameError, RequestCode::getNameDict());

        //////////////////////////////////
        // Test trans to display value. //
        //////////////////////////////////

        $this->assertEquals(
            RequestCode::transValue(RequestCode::SUCCESS),
            RequestCode::transName($nameSuccess)
        );

        $this->assertEquals(
            RequestCode::transValue(RequestCode::ERROR),
            RequestCode::transName($nameError)
        );
    }

    /**
     * @afterClass
     */
    public static function done()
    {
        echo PHP_EOL . PHP_EOL . 'Base test already executed.';
    }

}
