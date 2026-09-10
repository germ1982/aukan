<?php

namespace app\modules\api\controllers;

use Yii;
use app\modules\api\services\SectoresService;

class SectoresController extends BaseApiController
{
    public function actionBuscar()
    {
        $idoficina = Yii::$app->request->get('idoficina');

        if (!$idoficina) {
            $idoficina = Yii::$app->request->post('idoficina');
        }

        if (!$idoficina) {
            return $this->error('Debe informar el parámetro idoficina');
        }

        $service = new SectoresService();

        return $this->success(
            $service->buscarPorOficina($idoficina)
        );
    }

    public function actionEquipos()
    {
        $idDispositivo = Yii::$app->request->get('idDispositivo');

        if (!$idDispositivo) {
            $idDispositivo = Yii::$app->request->post('idDispositivo');
        }

        if (!$idDispositivo) {
            return $this->error('Debe informar el parámetro idDispositivo');
        }

        $service = new SectoresService();

        return $this->success(
            $service->obtenerEquiposPorDispositivo($idDispositivo)
        );
    }
}
