<?php

namespace tests\models;

use yii\db\ActiveRecord;
use corpsepk\yml\behaviors\YmlCategoryBehavior;

class Category extends ActiveRecord
{
    public $id;
    public $name;
    public $parentId;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'ymlCategory' => [
                'class' => YmlCategoryBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['id', 'name', 'parentId']);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'id' => $model->id,
                        'name' => $model->name,
                        'parentId' => $model->parentId,
                    ];
                }
            ],
        ];
    }
}