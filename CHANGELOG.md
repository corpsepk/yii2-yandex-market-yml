## 0.8 (2024-04-09)

### Added
* Offer->condition

### Changed
* Minimum php version - 8.1
* Temporary remove .github/workflows


## 0.7 (2020-12-22)

#### Added
* Offer->customElements

#### Changed
* Switch Travis-ci on GitHub actions



## 0.6 (2018-12-19)

#### Changed
* Replace constant `YmlOfferBehavior::BATCH_MAX_SIZE` with public property `batchMaxSize` 
* Render items even if they contain errors ([4c0292d](https://github.com/corpsepk/yii2-yandex-market-yml/commit/4c0292d))



## 0.5 (2018-12-19)

#### Added
* Visualize errors on YII_ENV_DEV Resolve: [#11](https://github.com/corpsepk/yii2-yandex-market-yml/issues/11) ([4296d1f](https://github.com/corpsepk/yii2-yandex-market-yml/commit/4296d1f))



## 0.4 (2018-10-17)


#### Breaking changes
* Rename param Offer->sale_notes => sales_notes. Close [#9](https://github.com/corpsepk/yii2-yandex-market-yml/issues/9) ([b0ca7bb](https://github.com/corpsepk/yii2-yandex-market-yml/commit/b0ca7bb))

#### Added
* .travis.yml added php7.1 ([17a8c5b](https://github.com/corpsepk/yii2-yandex-market-yml/commit/17a8c5b))




## 0.3.2 (2017-06-01)

#### Changed
* "vendor" is not required element ([e84880d](https://github.com/corpsepk/yii2-yandex-market-yml/commit/e84880d))

#### Fixed
* fixed OfferModelTest::testValidateVendor() ([0064aa3](https://github.com/corpsepk/yii2-yandex-market-yml/commit/0064aa3))
* YandexMarketYmlModuleTest fixed ([c06204d](https://github.com/corpsepk/yii2-yandex-market-yml/commit/c06204d))