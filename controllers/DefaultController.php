<?php
/**
 * @link https://github.com/corpsepk/yii2-yandex-market-yml
 * @copyright Copyright (c) 2016 Corpsepk
 * @license http://opensource.org/licenses/MIT
 */

namespace corpsepk\yml\controllers;

use Yii;
use yii\web\Controller;
use corpsepk\yml\YandexMarketYml;

/**
 * @author Corpsepk
 * @package corpsepk\yml
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
        /**
         * @var YandexMarketYml $module
         */
        $module = $this->module;

        $ymlData = $module->cacheProvider->get($module->cacheKey);

        if (!$ymlData) {
            $shop = $module->buildShop();
            $shop->validate();

            if ($shop->hasErrors()) {
                $module->logErrors($shop);

                if (YII_ENV_DEV) {
                    // Render errors in `dev` environment
                    return $this->render('errors', ['shop' => $shop]);
                }
            }

            $ymlData = $module->buildYml($shop);

            // Build cache if no errors
            if (!$shop->hasErrors()) {
                $module->cacheProvider->set($module->cacheKey, $ymlData, $module->cacheExpire);
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/xml');

        if ($module->enableGzip) {
            $ymlData = gzencode($ymlData);
            $headers->add('Content-Encoding', 'gzip');
            $headers->add('Content-Length', strlen($ymlData));
        }

        return $ymlData;
    }
}
