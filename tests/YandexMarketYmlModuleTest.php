<?php

namespace tests;

use Yii;
use tests\models\Category;
use corpsepk\yml\models\Shop;
use corpsepk\yml\models\Offer;
use corpsepk\yml\YandexMarketYml;

/**
 * BuildYmlTest
 */
class YandexMarketYmlModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildYml()
    {
        Yii::$app->setModule('YandexMarketYml', [
            'class' => YandexMarketYml::className(),
            'cacheProvider' => new \yii\caching\DummyCache(),
        ]);

        /** @var YandexMarketYml $module */
        $module = Yii::$app->getModule('YandexMarketYml');
        $yml = $module->createControllerByID('default')->renderPartial('index', [
            'shop' => new Shop([
                'name' => 'MyCompanyName',
                'company' => 'LTD MyCompanyName',
                'url' => 'http://example.com',
                'currencies' => [
                    ['id' => 'RUR', 'rate' => 1]
                ],
                'categories' => [
                    new Category([
                        'id' => 1,
                        'name' => 'First Category',
                        'parentId' => null,
                    ]),
                ],
                'offers' => [
                    new Offer([
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
                    ]),
                    new Offer([
                        'id' => 2,
                        'available' => false,
                        'url' => 'http://example.com/item/2',
                        'price' => 2360,
                        'currencyId' => 'RUR',
                        'categoryId' => 1,
                        'picture' => 'http://example.com/images/2.jpg',
                        'name' => 'T-shirt',
                        'vendor' => 'Manufacturer',
                        'description' => 'Items description',
                    ]),
                ]
            ]),
        ]);
        $expected = file_get_contents(__DIR__ . '/data/yml.bin');
        // TODO mock date function, and remove this
        $actual = preg_replace('/yml_catalog date="(.*?)"/', 'yml_catalog date="2017-01-01 10:00"', $yml);

        $this->assertEquals($expected, $actual);
    }
}