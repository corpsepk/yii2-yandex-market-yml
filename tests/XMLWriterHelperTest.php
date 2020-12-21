<?php
namespace tests;

use PHPUnit_Framework_TestCase;
use corpsepk\yml\models\Offer;
use corpsepk\yml\helpers\XMLWriterHelper;

class XMLWriterHelperTest extends PHPUnit_Framework_TestCase
{
    public function testCustomElements()
    {
        $helper = new XMLWriterHelper();
        $offer = new Offer([
            'id' => 1,
            'available' => true,
            'url' => 'http://example.com/item/1',
            'price' => 1560,
            'currencyId' => 'RUR',
            'categoryId' => 1,
            'picture' => 'http://example.com/images/1.jpg',
            'name' => 'Jacket',
            'vendor' => 'Manufacturer',
            'description' => 'Item description',
            'customElements' => [
                [
                    'outlets' => '<outlet id="1" instock="30" />'
                ]
            ],
        ]);

        $expected = '<offer id="1" available="1"><outlets><outlet id="1" instock="30" /></outlets><url>http://example.com/item/1</url><price>1560</price><currencyId>RUR</currencyId><categoryId>1</categoryId><picture>http://example.com/images/1.jpg</picture><name>Jacket</name><vendor>Manufacturer</vendor><description>Item description</description></offer>';

        $this->assertEquals($expected, $helper->renderOffer($offer));
    }
}
