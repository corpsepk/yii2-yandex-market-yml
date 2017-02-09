Yandex.Market YML Module for Yii2
==========================
Yii2 module for automatically generation [Yandex.Market YML](https://yandex.ru/support/webmaster/goods-prices/technical-requirements.xml).

[![Build Status](https://img.shields.io/travis/corpsepk/yii2-yandex-market-yml/master.svg?style=flat)](https://travis-ci.org/corpsepk/yii2-yandex-market-yml)
[![Code Coverage](https://scrutinizer-ci.com/g/corpsepk/yii2-yandex-market-yml/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/corpsepk/yii2-yandex-market-yml/?branch=master)

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

* Either run

```
php composer.phar require --prefer-dist "corpsepk/yii2-yandex-market-yml" "~0.2"
```

or add

```json
"corpsepk/yii2-yandex-market-yml" : "~0.2"
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
        'enableGzip' => true, // default is false
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
        ['pattern' => 'yandex-market', 'route' => 'yml/default/index', 'suffix' => '.yml'],
    ],
],
```


Configure models
------------
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
                ]);
            }
        ],
    ];
}
```


Useful links
------------
Yandex XML validator - https://webmaster.yandex.ru/tools/xml-validator/