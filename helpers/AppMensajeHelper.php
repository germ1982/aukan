<?php

namespace app\helpers;

use Yii;
use yii\web\View;

class AppMensajeHelper
{
    // Registra el archivo JS (JavaScript) directamente desde el disco
    public static function registerJs()
    {
        $jsFile = __DIR__ . '/AppMensajeHelper.js';
        if (file_exists($jsFile)) {
            Yii::$app->view->registerJs(file_get_contents($jsFile), View::POS_HEAD);
        }
    }

    public static function json($mensaje, $success = true, $forceReload = '#crud-datatable-pjax')
    {
        return [
            'success' => $success,
            'message' => $mensaje,
            'forceReload' => $forceReload
        ];
    }
}