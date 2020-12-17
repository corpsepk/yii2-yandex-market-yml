<?php

namespace corpsepk\yml\interfaces;

use corpsepk\yml\models\Offer;

interface YandexMarketOfferInterface
{
    /**
     * @return Offer
     */
    public function generateOffer();
}