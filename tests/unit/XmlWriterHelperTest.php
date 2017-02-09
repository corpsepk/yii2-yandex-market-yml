<?php
namespace tests;

use corpsepk\yml\helpers\XmlWriterHelper;

/**
 * OfferTestTest
 */
class XmlWriterHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertBoolToString()
    {
        $this->assertEquals('true', XmlWriterHelper::convertBoolToString(true));
        $this->assertNotEquals(true, XmlWriterHelper::convertBoolToString(true));
        $this->assertNotEquals('1', XmlWriterHelper::convertBoolToString(true));

        $this->assertEquals('false', XmlWriterHelper::convertBoolToString(false));
        $this->assertNotEquals('0', XmlWriterHelper::convertBoolToString(false));
        $this->assertNotEquals('', XmlWriterHelper::convertBoolToString(false));
    }
}
