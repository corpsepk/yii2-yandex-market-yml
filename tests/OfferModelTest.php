<?php
namespace tests;

use corpsepk\yml\models\Offer;
use yii\base\Security;

/**
 * OfferModelTest
 */
class OfferModelTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateId()
    {
        $model = new Offer();

        // Обязательный атрибут
        $model->id = null;
        $this->assertFalse($model->validate(['id']));

        $model->id = '123';
        $this->assertTrue($model->validate(['id']));

        $model->id = 123;
        $this->assertTrue($model->validate(['id']));

        $model->id = 'abc';
        $this->assertTrue($model->validate(['id']));

        // Максимальная длина - 20 символов
        $model->id = (new Security())->generateRandomString(21);
        $this->assertFalse($model->validate(['id']));
    }

    // TODO
//    public function testValidateType() {}

    // TODO
//    public function testValidateAvailable() {}

    // TODO
//    public function testValidateCBid() {}

    public function testValidateUrl()
    {
        $model = new Offer();

        // Необязательный элемент для магазинов-салонов
        $model->url = null;
        $this->assertTrue($model->validate(['url']));

        // Максимальная длина - 512 символов
        $model->url = (new Security())->generateRandomString(10);
        $this->assertTrue($model->validate(['url']));

        // Максимальная длина - 512 символов
        $model->url = (new Security())->generateRandomString(513);
        $this->assertFalse($model->validate(['url']));
    }

    public function testValidatePrice()
    {
        $model = new Offer();

        // Обязательный элемент
        $model->price = null;
        $this->assertFalse($model->validate(['price']));
    }

    public function testValidateOldPrice()
    {
        $model = new Offer();

        // В <oldprice> указывается старая цена товара, которая должна быть обязательно выше новой цены (<price>)
        $model->price = null;
        $model->oldprice = 2;
        $this->assertFalse($model->validate(['price', 'oldprice']));

        // В <oldprice> указывается старая цена товара, которая должна быть обязательно выше новой цены (<price>)
        $model->price = 2;
        $model->oldprice = 1;
        $this->assertFalse($model->validate(['oldprice']));

        // В <oldprice> указывается старая цена товара, которая должна быть обязательно выше новой цены (<price>)
        $model->price = 1;
        $model->oldprice = 2;
        $this->assertTrue($model->validate(['oldprice']));

        // Скидка может показываться в процентах.
        $model->price = 20;
        $model->oldprice = '10%';
        $this->assertTrue($model->validate(['oldprice']));

        $model->price = '20';
        $model->oldprice = '10%';
        $this->assertTrue($model->validate(['oldprice']));
    }

    public function testValidateCurrencyId()
    {
        $model = new Offer();

        foreach (Offer::CURRENCY_AVAILABLE as $currency)
        {
            $model->currencyId = $currency;
            $this->assertTrue($model->validate(['currencyId']));
        }

        $model->currencyId = 'NON';
        $this->assertFalse($model->validate(['currencyId']));

        $model->currencyId = null;
        $this->assertFalse($model->validate(['currencyId']));
    }

    public function testValidateCategoryId()
    {
        $model = new Offer();

        // Идентификатор категории товара (целое число не более 18 знаков)
        for ($i = 1; $i <= 18; $i++) {
            $model->categoryId .= rand(1, 9);
        }
        $this->assertTrue($model->validate(['categoryId']));

        // Идентификатор категории товара (целое число не более 18 знаков)
        for ($i = 1; $i <= 19; $i++) {
            $model->categoryId .= rand(1, 9);
        }
        $this->assertTrue($model->validate(['categoryId']));

        // Идентификатор категории товара (целое число не более 18 знаков)
        $model->categoryId = (new Security())->generateRandomKey(19);
        $this->assertFalse($model->validate(['categoryId']));

        // Элемент offer может содержать только один элемент categoryId
        $model->categoryId = [1, 2, 3];
        $this->assertFalse($model->validate(['categoryId']));

        // Идентификатор категории товара (целое число не более 18 знаков)
        $model->categoryId = 'abc';
        $this->assertFalse($model->validate(['categoryId']));

        // Идентификатор категории товара не может быть равен нулю
        $model->categoryId = 0;
        $this->assertFalse($model->validate(['categoryId']));

        // Идентификатор категории товара не может быть равен нулю
        $model->categoryId = '0';
        $this->assertFalse($model->validate(['categoryId']));
    }

    public function testValidatePicture()
    {
        $model = new Offer();
        $randomString20Symbols = (new Security())->generateRandomString(20);
        $randomString513Symbols = (new Security())->generateRandomString(513);

        $model->picture = null;
        $this->assertTrue($model->validate(['picture']));

        // Может быть строкой
        $model->picture = $randomString20Symbols;
        $this->assertTrue($model->validate(['picture']));

        // Может быть массивом
        $model->picture = [$randomString20Symbols, $randomString20Symbols];
        $this->assertTrue($model->validate(['picture']));

        // Максимальная длина URL — 512 символов
        $model->picture = $randomString513Symbols;
        $this->assertFalse($model->validate(['picture']));

        // Максимальная длина URL — 512 символов
        $model->picture = [$randomString513Symbols, $randomString20Symbols];
        $this->assertFalse($model->validate(['picture']));

        // Массив должен состоять не более чем из 10ти элементов
        $model->picture = array_fill(0, 11, $randomString20Symbols);
        $this->assertFalse($model->validate(['picture']));
    }

    // TODO
//    public function testValidateStore() {}

    // TODO
//    public function testValidatePickup() {}

    // TODO
//    public function testValidateDelivery() {}

    public function testValidateLocalDeliveryCost()
    {
        $model = new Offer();

        $model->local_delivery_cost = null;
        $this->assertTrue($model->validate(['local_delivery_cost']));

        $model->local_delivery_cost = 100;
        $this->assertTrue($model->validate(['local_delivery_cost']));

        $model->local_delivery_cost = '100';
        $this->assertTrue($model->validate(['local_delivery_cost']));

        $model->local_delivery_cost = 'abc';
        $this->assertFalse($model->validate(['local_delivery_cost']));
    }

    // TODO
//    public function testValidateTypePrefix() {}

    // TODO
//    public function testValidateName() {}

    public function testValidateVendor()
    {
        $model = new Offer();

        // Обязательный элемент
        $model->vendor = null;
        $this->assertFalse($model->validate(['vendor']));

        $model->vendor = '123abc';
        $this->assertTrue($model->validate(['vendor']));

        $model->vendor = 'abc';
        $this->assertTrue($model->validate(['vendor']));

        $model->vendor = 123;
        $this->assertFalse($model->validate(['vendor']));
    }

    public function testValidateVendorCode()
    {
        $model = new Offer();

        $model->vendorCode = null;
        $this->assertTrue($model->validate(['vendorCode']));

        $model->vendorCode = 123;
        $this->assertTrue($model->validate(['vendorCode']));

        $model->vendorCode = '123';
        $this->assertTrue($model->validate(['vendorCode']));

        $model->vendorCode = 'abc';
        $this->assertTrue($model->validate(['vendorCode']));
    }

    // TODO
//    public function testValidateModel() {}

    public function testValidateDescription()
    {
        $model = new Offer();

        $model->description = null;
        $this->assertTrue($model->validate(['description']));

        $model->description = (new Security())->generateRandomString(1000);
        $this->assertTrue($model->validate(['description']));
    }

    public function testValidateSaleNotes()
    {
        $model = new Offer();

        // Необязательный элемент
        $model->sale_notes = null;
        $this->assertTrue($model->validate(['sale_notes']));

        // Допустимая длина текста в элементе — 50 символов
        $model->sale_notes = (new Security())->generateRandomString(50);
        $this->assertTrue($model->validate(['sale_notes']));

        // Допустимая длина текста в элементе — 50 символов
        $model->sale_notes = (new Security())->generateRandomString(51);
        $this->assertFalse($model->validate(['sale_notes']));
    }

    // TODO
//    public function testValidateManufacturerWarranty() {}

    // TODO
//    public function testValidateSellerWarranty() {}

    // TODO
//    public function testValidateCountryOfOrigin() {}

    // TODO
//    public function testValidateDownloadable() {}

    // TODO
//    public function testValidateAdult() {}

    // TODO
//    public function testValidateAge() {}

    public function testValidateBarcode()
    {
        $model = new Offer();

        $model->barcode = null;
        $this->assertTrue($model->validate(['barcode']));

        $model->barcode = (new Security())->generateRandomString(20);
        $this->assertTrue($model->validate(['barcode']));

        $model->barcode = [
            (new Security())->generateRandomString(20),
            (new Security())->generateRandomString(20)
        ];
        $this->assertTrue($model->validate(['barcode']));
    }

    // TODO
//    public function testValidateCpa() {}

    // TODO
//    public function testValidateRec() {}

    // TODO
//    public function testValidateExpiry() {}

    // TODO
//    public function testValidateWeight() {}

    // TODO
//    public function testValidateDimensions() {}

    public function testValidateParam()
    {
        $model = new Offer();

        $model->param = null;
        $this->assertTrue($model->validate(['param']));

        $model->param = [];
        $this->assertTrue($model->validate(['param']));

        $model->param = [
            ['name' => 'Размер', 'value' => '42-44'],
            ['name' => 'Цвет', 'value' => 'Красный']
        ];
        $this->assertTrue($model->validate(['param']));

        $model->param = ['Размер', '42-44'];
        $this->assertFalse($model->validate(['param']));

        $model->param = [
            ['name' => 'Размер', 'value' => '42-44'],
            ['name' => 'Цвет', 1 => 'Красный'],
        ];
        $this->assertFalse($model->validate(['param']));
    }
}
