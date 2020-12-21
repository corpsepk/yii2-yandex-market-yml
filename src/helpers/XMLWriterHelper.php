<?php

namespace corpsepk\yml\helpers;

use XMLWriter;
use yii\helpers\Html;
use corpsepk\yml\models\Offer;

class XMLWriterHelper
{
    /**
     * @param Offer $offer
     * @return string
     */
    public function renderOffer($offer)
    {
        $writer = new XMLWriter();
        $writer->openMemory();

        $writer->startElement('offer');

        foreach ($offer->offerElementAttributes as $attribute) {
            if (!empty($offer->$attribute)) {
                $writer->writeAttribute($attribute, Html::encode($offer->$attribute));
            }
        }

        foreach ($offer->customElements as $attribute) {
            if (!is_array($attribute)) {
                continue;
            }

            foreach ($attribute as $attrName => $attrValue) {
                if (is_array($attrValue)) {
                    $writer->startElement($attrName);
                    foreach ($attrValue as $name => $value) {
                        $writer->writeElement($name, Html::encode($value));
                    }
                    $writer->endElement();
                } else {
                    $writer->startElement($attrName);
                    $writer->writeRaw($attrValue);
                    $writer->endElement();
                }
            }
        }

        foreach ($offer->getOfferElements() as $attribute) {
            if (empty($offer->$attribute)) {
                continue;
            }

            if (is_array($offer->$attribute)) {
                foreach ($offer->$attribute as $value) {
                    $writer->writeElement($attribute, Html::encode($value));
                }
            } else {
                $writer->writeElement($attribute, Html::encode($offer->$attribute));
            }
        }

        if (is_array($offer->param)) {
            foreach ($offer->param as $param) {
                $writer->startElement('param');
                $writer->writeAttribute('name', $param['name']);
                $writer->text($param['value']);
                $writer->endElement();
            }
        }

        $writer->endElement();

        return $writer->outputMemory();
    }
}