<?php

namespace app\modules\api\services;

use app\services\PersonaManager;

class PersonasService
{
    public function buscarPorDni($dni)
    {
        // Validación
        if (empty($dni)) {
            return [
                'success' => false,
                'mensaje' => 'Debe informar el DNI'
            ];
        }

        $manager = new PersonaManager();

        $persona = $manager->buscarPorDni($dni);

        if (!$persona) {
            return [
                'success' => false,
                'mensaje' => 'Persona no encontrada'
            ];
        }

        return [
            'success' => true,
            'mensaje' => 'Persona encontrada',
            'data' => [
                'idpersona' => $persona->idpersona,
                'documento' => $persona->documento,
                'apellido' => $persona->apellido,
                'nombre' => $persona->nombre,
                'fecha_nacimiento' => $persona->fecha_nacimiento,
                'genero' => $persona->genero,
            ]
        ];
    }
}
