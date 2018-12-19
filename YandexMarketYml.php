<?php
/**
 * @link https://github.com/corpsepk/yii2-yandex-market-yml
 * @copyright Copyright (c) 2016 Corpsepk
 * @license http://opensource.org/licenses/MIT
 */

namespace corpsepk\yml;

use Yii;
use yii\base\Module;
use yii\caching\Cache;
use yii\base\InvalidConfigException;
use corpsepk\yml\models\Shop;
use corpsepk\yml\behaviors\YmlOfferBehavior;
use corpsepk\yml\behaviors\YmlCategoryBehavior;

/**
 * Yii2 module for automatically generating Yandex.Market YML.
 *
 * @author Corpsepk
 * @package corpsepk\yml
 */
class YandexMarketYml extends Module
{
    public $controllerNamespace = 'corpsepk\yml\controllers';

    /**
     * @var int
     */
    public $cacheExpire = 86400;

    /**
     * @var Cache|string
     */
    public $cacheProvider = 'cache';

    /**
     * @var string
     */
    public $cacheKey = 'YandexMarketYml';

    /**
     * Use php's gzip compressing.
     * @var boolean
     */
    public $enableGzip = false;

    /**
     * @var YmlCategoryBehavior
     */
    public $categoryModel;

    /**
     * @var array
     */
    public $offerModels;

    /**
     * @var array
     */
    public $shopOptions = [];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (is_string($this->cacheProvider)) {
            $this->cacheProvider = Yii::$app->{$this->cacheProvider};
        }

        if (!$this->cacheProvider instanceof Cache) {
            throw new InvalidConfigException('Invalid `cacheKey` parameter was specified.');
        }
    }

    /**
     * Build and cache a yandex.market yml
     * @return string
     */
    public function buildYml()
    {
        $shop = new Shop();
        $shop->setAttributes($this->shopOptions);

        /**
         * @var YmlCategoryBehavior $categoryModel
         */
        $categoryModel = new $this->categoryModel;
        $shop->categories = $categoryModel->generateCategories();

        $offers = [[]];
        foreach ($this->offerModels as $modelName) {
            /**
             * @var YmlOfferBehavior $model
             */
            if (is_array($modelName)) {
                $model = new $modelName['class'];
            } else {
                $model = new $modelName;
            }

            $offers[] = $model->generateOffers();
        }
        $shop->offers = array_merge(...$offers);

        if (!$shop->validate()) {
            return $this->createControllerByID('default')->renderPartial('errors', [
                'shop' => $shop,
            ]);
        }

        $ymlData = $this->createControllerByID('default')->renderPartial('index', [
            'shop' => $shop,
        ]);
        $this->cacheProvider->set($this->cacheKey, $ymlData, $this->cacheExpire);

        return $ymlData;
    }
}
