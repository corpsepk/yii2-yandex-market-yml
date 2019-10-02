# Simple example how to use module in console


#### Config 
Add a new module in `modules` section of your application's configuration file `app/config/console.php`, for example:

```php
'modules' => [
    'YandexMarketYml' => [
        'class' => 'corpsepk\yml\YandexMarketYml',
        'enableGzip' => true, // default is false
        'cacheExpire' => 1, // 1 second. Default is 24 hours
        'shopOptions' => [
            'name' => 'MyCompanyName',
            'company' => 'LTD MyCompanyName',
            'url' => 'http://example.com',
            'currencies' => [
                [
                    'id' => 'RUR',
                    'rate' => 1
                ]
            ],
        ],
    ],
    ...
],
```

#### Controller

Create controller `app/commands/YandexMarketController`, for example:

```php
<?php

namespace app\commands;

use Yii;
use yii\helpers\FileHelper;
use yii\console\Controller;
use corpsepk\yml\models\Shop;
use corpsepk\yml\models\Offer;
use corpsepk\yml\YandexMarketYml;

class YandexMarketController extends Controller
{
    public function actionBuildYml()
    {
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
                    [
                        'id' => 1,
                        'name' => 'First Category',
                        'parentId' => null,
                    ],
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
                ]
            ]),
        ]);

        $dir = Yii::getAlias('@app/web/yml');
        FileHelper::createDirectory($dir);

        $fileName = $dir . '/yandex-market.yml';
        file_put_contents($fileName, $yml);
    }
}
```