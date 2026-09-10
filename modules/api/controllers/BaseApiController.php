<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseApiController extends Controller
{
    /**
     * La API no utiliza CSRF porque es consumida por otros sistemas.
     */
    public $enableCsrfValidation = false;

    /**
     * Fuerza todas las respuestas en formato JSON.
     */
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    /**
     * Respuesta exitosa estándar.
     */
    protected function success($data = null, $message = 'OK')
    {
        return [
            'success' => true,
            'message' => $message,
            'data'    => $data
        ];
    }

    /**
     * Respuesta de error estándar.
     */
    protected function error($message, $code = 400)
    {
        Yii::$app->response->statusCode = $code;

        return [
            'success' => false,
            'message' => $message
        ];
    }

    /**
     * Endpoint de prueba.
     */
    public function actionPing()
    {
        return $this->success([
            'fecha' => date('Y-m-d H:i:s')
        ], 'API funcionando');
    }
}