<?php
/**
 * @link https://github.com/corpsepk/yii2-yandex-market-yml
 * @copyright Copyright (c) 2016 Corpsepk
 * @license http://opensource.org/licenses/MIT
 */

namespace corpsepk\yml\models;

use Yii;
use yii\base\Model;
use yii\data\BaseDataProvider;
use yii\helpers\Html;

/**
 * Требования к формату и методу передачи данных о товарных предложениях
 * @link https://yandex.ru/support/market-tech-requirements/
 *
 *
 * Описание элементов, входящих в shop
 * @link https://yandex.ru/support/partnermarket/elements/shop.html
 */
class Shop extends Model
{
    /**
     * Короткое название магазина.
     * В названии нельзя использовать слова, которые не относятся к наименованию магазина
     * (например «лучший», «дешевый»), указывать номер телефона и т. п.
     *
     * Название магазина должно совпадать с фактическим названием,
     * которое публикуется на сайте. Если требование не соблюдается,
     * Яндекс.Маркет может самостоятельно изменить название без уведомления магазина.
     *
     * @var string
     */
    public $name;

    /**
     * Полное наименование компании, владеющей магазином.
     * Не публикуется, используется для внутренней идентификации.
     *
     * @var string
     */
    public $company;

    /**
     * URL-адрес главной страницы магазина.
     * Допускаются кириллические ссылки.
     *
     * @var string
     */
    public $url;

    /**
     * @var string|null
     */
    public $phone;

    /**
     * Система управления контентом, на основе которой работает магазин (CMS).
     *
     * @var string|null
     */
    public $platform;

    /**
     * Версия CMS.
     *
     * @var string|null
     */
    public $version;

    /**
     * Наименование агентства, которое оказывает техническую поддержку интернет-магазину
     * и отвечает за работоспособность сайта.
     *
     * @var string|null
     */
    public $agency;

    /**
     * Контактный адрес разработчиков CMS или агентства, осуществляющего техподдержку.
     *
     * @var array|string|null
     */
    public $email;

    /**
     * Список курсов валют магазина.
     *
     * @var array
     */
    public $currencies;

    /**
     * Список категорий магазина.
     *
     * @var array
     */
    public $categories;

    /** @var string|null */
    public $store;

    /** @var string|null */
    public $pickup;

    /** @var string|null */
    public $delivery;

    /** @var string|null */
    public $deliveryIncluded;

    /** @var string|null */
    public $local_delivery_cost;

    /** @var true|null */
    public $adult;

    /** @var Offer[] */
    public $offers = [];

    /**
     * Указанный элемент предназначен для возможности управления участием всего магазина (всех товарных предложений магазина) в программе «Заказ на Маркете». Значение элемента cpa учитывается только в том случае, если в Веб-интерфейсе Магазин подтвердил свое желание размещать Товарные предложения в программе «Заказ на Маркете».
     * Элемент может принимать следующие значения:
     * «0» — товар/YML-файл не участвует в программе «Заказ на Маркете»;
     * «1» — товар/YML-файл участвует в программе «Заказ на Маркете».
     * Значение по умолчанию для всех товарных предложений магазина — «1».
     * Если указано другое значение, то оно автоматически принимается равным «0».
     *
     * @var $string
     */
    public $cpa;

    /** @var array */
    public $optionalAttributes = [
        'phone', 'platform', 'version', 'agency', 'email',
        'store', 'pickup', 'delivery', 'deliveryIncluded',
        'local_delivery_cost', 'adult'
    ];

    /** @var BaseDataProvider */
    public $dataProvider;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'company', 'url'], 'required'],
            ['name', 'string', 'max' => 20],
            [['company', 'url'], 'string'],

            ['currencies', 'required'],
            ['currencies', 'validateCurrencies'],

            [
                [
                    'phone', 'platform', 'version', 'agency',
                    'store', 'pickup', 'delivery', 'deliveryIncluded',
                    'local_delivery_cost'
                ],
                'string'
            ],

            ['adult', 'boolean'],
            ['email', 'safe'],

            ['cpa', 'boolean'],
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     * @return bool
     */
    public function validateCurrencies($attribute, $params)
    {
        if (!is_array($this->currencies)) {
            $this->addError('currencies', 'Currencies must be an array');
            return false;
        }

        foreach ($this->currencies as $currency) {
            if (!isset($currency['id'], $currency['rate'])) {
                $this->addError('currencies', 'Currency must contain "id" and "rate" keys');
                return false;
            }
        }

        return true;
    }

    public function validateOffers()
    {
        foreach ($this->offers as $offer) {
            if (!$offer->validate()) {
                foreach ($offer->getFirstErrors() as $error) {
                    Yii::error(Html::encode($error));
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $this->validateOffers();
    }
}