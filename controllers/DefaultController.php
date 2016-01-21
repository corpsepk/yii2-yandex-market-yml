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
        /** @var YandexMarketYml $module */
        $module = $this->module;

        if (!$ymlData = $module->cacheProvider->get($module->cacheKey)) {
            $ymlData = $module->buildYml();
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
