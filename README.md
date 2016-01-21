Yandex.Market YML Module for Yii2
==========================
Yii2 module for automatically generation [Yandex.Market YML](https://yandex.ru/support/webmaster/goods-prices/technical-requirements.xml).

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

* Either run

```
php composer.phar require --prefer-dist "corpsepk/yii2-yandex-market-yml" "@dev"
```

or add

```json
"corpsepk/yii2-yandex-market-yml" : "@dev"
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
                    'url' => $model->getUrl(true),
                    'price' => $model->getPrice(),
                    'currencyId' => 'RUR',
                    'categoryId' => $model->category_id,
                    'name' => $model->name,
                    'vendor' => $model->brand ? $model->brand->name : null,
                    'description' => $model->description,
                ]);
            }
        ],
    ];
}
```
