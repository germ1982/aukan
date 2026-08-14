<?php

namespace app\modules\api\controllers;

use Yii;
use app\modules\api\services\PersonasService;

class PersonasController extends BaseApiController
{
    public function actionPing()
    {
        return $this->success([
            'fecha' => date('Y-m-d H:i:s')
        ], 'API Personas funcionando');
    }

    public function actionBuscar()
    {
        $dni = Yii::$app->request->post('dni');

        if (!$dni) {
            $dni = Yii::$app->request->get('dni');
        }

        if (!$dni) {
            $body = Yii::$app->request->bodyParams;
            $dni = $body['dni'] ?? null;
        }

        if (empty($dni)) {
            return $this->error('Debe informar el parámetro dni');
        }

        $service = new PersonasService();

        return $service->buscarPorDni($dni);
    }
}
