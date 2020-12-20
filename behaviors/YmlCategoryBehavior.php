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
 * YmlCategory Behavior for YandexMarketYml Yii2 module.
 *
 * For example:
 *
 * ```php
 * public function behaviors()
 * {
 *  return [
 *       'ymlCategory' => [
 *           'class' => YmlCategoryBehavior::className(),
 *           'scope' => function ($model) {
 *               $model->select(['id', 'name', 'parent_id']);
 *               $model->andWhere(['is_deleted' => 0]);
 *           },
 *           'dataClosure' => function ($model) {
 *              return [
 *                  'id' => $model->id,
 *                  'name' => $model->name,
 *                  'parentId' => $model->parent_id
 *              ];
 *          }
 *       ],
 *  ];
 * }
 * ```
 *
 * @see https://yandex.ru/support/webmaster/goods-prices/technical-requirements.xml
 * @author Corpsepk
 * @package corpsepk\YandexMarketYml
 */
class YmlCategoryBehavior extends Behavior
{
    const BATCH_MAX_SIZE = 100;

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
    public function generateCategories()
    {
        $result = [];
        $n = 0;

        /** @var ActiveRecord $owner */
        $owner = $this->owner;
        $query = $owner::find();
        if (is_callable($this->scope)) {
            call_user_func($this->scope, $query);
        }

        foreach ($query->each(self::BATCH_MAX_SIZE) as $model) {
            $data = call_user_func($this->dataClosure, $model);

            if (empty($data)) {
                continue;
            }

            $result[$n]['id'] = $data['id'];
            $result[$n]['name'] = $data['name'];
            $result[$n]['parentId'] = isset($data['parentId'])
                ? $data['parentId']
                : null;

            ++$n;
        }
        return $result;
    }
}
