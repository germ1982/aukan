<?php

namespace app\services;

use app\models\Persona;

class PersonaManager
{
    public function buscarPorDni($dni)
    {
        return Persona::find()
            ->where(['documento' => $dni])
            ->one();
    }
}
