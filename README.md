Yandex.Market YML Module for Yii2
==========================
Yii2 module for automatically generation [Yandex.Market YML](https://yandex.ru/support/webmaster/goods-prices/technical-requirements.xml).

[![Latest Version](https://img.shields.io/github/tag/corpsepk/yii2-yandex-market-yml.svg?style=flat-square&label=release)](https://github.com/corpsepk/yii2-yandex-market-yml/tags)
[![Build Status](https://github.com/corpsepk/yii2-yandex-market-yml/workflows/build/badge.svg)](https://github.com/corpsepk/yii2-yandex-market-yml/actions)
[![Quality Score](https://img.shields.io/scrutinizer/g/corpsepk/yii2-yandex-market-yml.svg?style=flat-square)](https://scrutinizer-ci.com/g/corpsepk/yii2-yandex-market-yml)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg?style=flat-square)](https://php.net/)

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

* Either run

```
php composer.phar require --prefer-dist "corpsepk/yii2-yandex-market-yml" "~0.8"
```

or add

```
"corpsepk/yii2-yandex-market-yml": "~0.8"
```

to the `require` section of your application's `composer.json` file.


Configure config
------------
Configure the `cache` component of your application's configuration file, for example:

```php
'components' => [
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
]
```

Add a new module in `modules` section of your application's configuration file, for example:

```php
'modules' => [
    'YandexMarketYml' => [
        'class' => 'corpsepk\yml\YandexMarketYml',
        'cacheExpire' => 1, // 1 second. Default is 24 hours
        'categoryModel' => 'app\models\Category',
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
        'offerModels' => [
            ['class' => 'app\models\Item'],
        ],
    ],
],
```

Add a new rule for `urlManager` of your application's configuration file, for example:

```php
'urlManager' => [
    'rules' => [
        ['pattern' => 'yandex-market', 'route' => 'YandexMarketYml/default/index', 'suffix' => '.yml'],
    ],
],
```


Configure `Category` model
------------
https://yandex.ru/support/partnermarket/elements/categories.html

Add behavior in the AR category model, for example:

```php
use corpsepk\yml\behaviors\YmlCategoryBehavior;

public function behaviors()
{
    return [
        'ymlCategory' => [
            'class' => YmlCategoryBehavior::className(),
            'scope' => function ($model) {
                /** @var \yii\db\ActiveQuery $model */
                $model->select(['id', 'name', 'parent_id']);
            },
            'dataClosure' => function ($model) {
                /** @var self $model */
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                    'parentId' => $model->parent_id
                ];
            }
        ],
    ];
}
```

Configure `Offer` models
------------

https://yandex.ru/support/products/offers.html

Add behavior in the AR models, for example:

```php
use corpsepk\yml\behaviors\YmlOfferBehavior;
use corpsepk\yml\models\Offer;

public function behaviors()
{
    return [
        'ymlOffer' => [
            'class' => YmlOfferBehavior::className(),
            'scope' => function ($model) {
                /** @var \yii\db\ActiveQuery $model */
                $model->andWhere(['is_deleted' => false]);
            },
            'dataClosure' => function ($model) {
                /** @var self $model */
                return new Offer([
                    'id' => $model->id,
                    'url' => $model->getUrl(true), // absolute url e.g. http://example.com/item/1256
                    'price' => $model->getPrice(),
                    'currencyId' => 'RUR',
                    'categoryId' => $model->category_id,
                    'picture' => $model->cover ? $model->cover->getUrl() : null,
                    /**
                     * Or as array
                     * don't forget that yandex-market accepts 10 pictures max
                     * @see https://yandex.ru/support/partnermarket/picture.xml
                     */
                    'picture' => ArrayHelper::map($model->images, 'id', function ($image) {
                        return $image->getUrl();
                    }),
                    'name' => $model->name,
                    'vendor' => $model->brand ? $model->brand->name : null,
                    'description' => $model->description,
                    'customElements' => [
                        [
                            'outlets' => '<outlet id="1" instock="30" />'
                        ]
                    ],
                    'condition' => new \corpsepk\yml\dto\Condition(
                        type: \corpsepk\yml\enums\ConditionType::PREOWNED,
                        quality: \corpsepk\yml\enums\ConditionQuality::EXCELLENT,
                        reason: 'Some scratches',
                    )
                ]);
            }
        ],
    ];
}
```

Testing
------------

```bash
./vendor/bin/phpunit
```

Howto
------------

[Use console command to build yml](https://github.com/corpsepk/yii2-yandex-market-yml/blob/master/docs/console-command-basic.md)


Useful links
------------
Yandex XML validator - https://webmaster.yandex.ru/tools/xml-validator/
