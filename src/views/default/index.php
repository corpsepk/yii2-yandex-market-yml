<?php
/**
 * @link https://github.com/corpsepk/yii2-yandex-market-yml
 * @copyright Copyright (c) 2016 Corpsepk
 * @license http://opensource.org/licenses/MIT
 *
 * @var $shop corpsepk\yml\models\Shop
 */

use yii\helpers\Html;
use corpsepk\yml\helpers\XMLWriterHelper;

// TODO add `ext-xmlwriter` requirement to composer.json
$writer = new XMLWriter();
$writer->openUri('php://output');

$writer->startDocument('1.0', 'UTF-8');
$writer->startDtd('yml_catalog SYSTEM "shops.dtd"');
$writer->endDtd();

$writer->startElement('yml_catalog');
$writer->writeAttribute('date', date('Y-m-d H:i'));
$writer->startElement('shop');

$writer->writeElement('name', Html::encode($shop->name));
$writer->writeElement('company', Html::encode($shop->company));
$writer->writeElement('url', Html::encode($shop->url));

foreach ($shop->optionalAttributes as $attribute) {
    if (empty($shop->$attribute)) {
        continue;
    }

    if (is_array($shop->$attribute)) {
        foreach ($shop->$attribute as $value) {
            $writer->writeElement($attribute, Html::encode($value));
        }
    } else {
        $writer->writeElement($attribute, Html::encode($shop->$attribute));
    }
}

// <currencies>
$writer->startElement('currencies');
foreach ($shop->currencies as $currency) {
    $writer->startElement('currency');
    $writer->writeAttribute('id', Html::encode($currency['id']));
    $writer->writeAttribute('rate', Html::encode($currency['rate']));
    $writer->endElement();
}
$writer->endElement();

// <categories>
$writer->startElement('categories');
foreach ($shop->categories as $category) {
    $writer->startElement('category');

    $writer->writeAttribute('id', Html::encode($category['id']));
    if ($category['parentId']) {
        $writer->writeAttribute('parentId', Html::encode($category['parentId']));
    }
    $writer->writeRaw(Html::encode($category['name']));

    $writer->endElement();
}
$writer->endElement();

// <offers>
$writer->startElement('offers');

$helper = new XMLWriterHelper();

foreach ($shop->offers as $offer) {
    if ($offer->errors) {
        continue;
    }

    $writer->writeRaw($helper->renderOffer($offer));
}
$writer->endElement();

$writer->fullEndElement();
$writer->fullEndElement();
$writer->endDocument();