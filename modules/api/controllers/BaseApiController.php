<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    /**
     * Devuelve una respuesta estándar.
     */
    protected function success($data = null, $message = 'OK')
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    protected function error($message, $code = 400)
    {
        Yii::$app->response->statusCode = $code;

        return [
            'success' => false,
            'message' => $message
        ];
    }
    public function actionPing()
    {
        return $this->success([
            'fecha' => date('Y-m-d H:i:s')
        ], 'API funcionando');
    }
}
