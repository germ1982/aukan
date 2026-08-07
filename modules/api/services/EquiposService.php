<?php

namespace app\modules\api\services;

use app\services\EquipoManager;

class EquiposService
{
    public function listar()
    {
        $manager = new EquipoManager();

        $equipos = $manager->listar();

        return [
            'success' => true,
            'cantidad' => count($equipos),
            'data' => $equipos
        ];
    }
}