<?php
/**
 * @link https://github.com/corpsepk/yii2-yandex-market-yml
 * @copyright Copyright (c) 2016 Corpsepk
 * @license http://opensource.org/licenses/MIT
 */

namespace corpsepk\yml\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;

/**
 * YmlOffer Behavior for YandexMarketYml Yii2 module.
 * @see https://yandex.ru/support/webmaster/goods-prices/technical-requirements.xml
 *
 * For example:
 *
 * ```php
 * public function behaviors()
 * {
 *  return [
 *       'ymlOffer' => [
 *           'class' => YmlOfferBehavior::className(),
 *           'scope' => function ($model) {
 *               /* @var \yii\db\ActiveQuery $model *\/
 *               $model->select(['id', 'name', 'parent_id']);
 *               $model->andWhere(['>', 'in_stock', 0]);
 *               $model->andWhere(['is_deleted' => 0]);
 *           },
 *           'dataClosure' => function ($model) {
 *              /** @var self $model *\/
 *              return new \corpsepk\yml\models\Offer([
 *                  'id' => $model->id,
 *                  'url' => $model->getUrl(),
 *                  'price' => $model->getPrice(),
 *                  'currencyId' => 'RUR',
 *                  'categoryId' => $model->catgory_id,
 *                  'picture' => $model->cover ? $model->cover->getUrl('1500x') : null,
 *                  'name' => $model->name,
 *                  'vendor' => $model->brand ? $model->brand->name : null,
 *                  ...
 *              ]);
 *          }
 *       ],
 *  ];
 * }
 * ```
 *
 * @author Corpsepk
 * @package corpsepk\yml
 */
class YmlOfferBehavior extends Behavior
{
    public $batchMaxSize = 100;

    /**
     * @var callable
     */
    public $dataClosure;

    /**
     * @var callable
     */
    public $scope;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!is_callable($this->dataClosure)) {
            throw new InvalidConfigException('YmlCategoryBehavior::$dataClosure isn\'t callable.');
        }
    }

    /**
     * @return array
     */
    public function generateOffers()
    {
        $result = [];

        /** @var ActiveRecord $owner */
        $owner = $this->owner;
        $query = $owner::find();
        if (is_callable($this->scope)) {
            call_user_func($this->scope, $query);
        }

        foreach ($query->each($this->batchMaxSize) as $model) {
            $data = call_user_func($this->dataClosure, $model);

            if (empty($data)) {
                continue;
            }

            $result[] = $data;
        }
        return $result;
    }
}
