<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use corpsepk\yml\models\Shop;
use corpsepk\yml\models\Offer;

/* @var $this yii\web\View */
/* @var $shop corpsepk\yml\models\Shop */

$this->params['breadcrumbs'][] = 'YandexMarketYml errors';
?>

<h1><?= Html::tag('code', Shop::class) ?> errors:</h1>

<?= Html::errorSummary($shop) ?>

<hr>

<h1><?= Html::tag('code', Offer::class) ?> erorrs:</h1>

<?php
foreach ($shop->offers as $offer) {
    /**
     * @var Offer $offer
     */
    if (!$offer->hasErrors()) {
        continue;
    }
    ?>
    <details>
        <summary>Offer id: <?= $offer->id ?></summary>
        <?= DetailView::widget([
            'model' => $offer,
            'attributes' => array_map(function($attribute) use ($offer) {
                return [
                    'attribute' => $attribute,
                    'format' => 'raw',
                    'value' => \yii\helpers\VarDumper::dumpAsString($offer->$attribute, 1, true),
                ];
            }, array_keys($offer->attributes)),
        ]) ?>
    </details>

    <?= Html::errorSummary($offer) ?>
    <br>
    <?php
}