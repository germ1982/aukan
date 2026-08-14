<?php

namespace app\modules\api\controllers;

use app\modules\api\services\EquiposService;

class EquiposController extends BaseApiController
{
    public function actionIndex()
    {
        $service = new EquiposService();

        return $service->listar();
    }
}