<?php

namespace app\modules\api\services;

use app\services\SectoresManager;

class SectoresService
{
    public function buscarPorOficina($idoficina)
    {
        $manager = new SectoresManager();

        return $manager->buscarPorOficina($idoficina);
    }

    public function obtenerEquiposPorDispositivo($idDispositivo)
    {
        $manager = new SectoresManager();

        $filas = $manager->obtenerEquiposPorDispositivo($idDispositivo);

        $equipos = [];

        foreach ($filas as $fila) {

            $idInventario = $fila['idinventario'];

            // Si todavía no existe el equipo, lo creamos
            if (!isset($equipos[$idInventario])) {

                $equipos[$idInventario] = [
                    'idorganismo' => $fila['idorganismo'],
                    'organismo' => $fila['organismo'],

                    'iddispositivo' => $fila['iddispositivo'],
                    'sector' => $fila['sector'],

                    'idinventario' => $fila['idinventario'],
                    'idarticulo' => $fila['idarticulo'],
                    'modelo' => $fila['modelo'],
                    'descripcion_articulo' => $fila['descripcion_articulo'],
                    'tipo_articulo' => $fila['tipo_articulo'],
                    'marca' => $fila['marca'],

                    'idoficina' => $fila['idoficina'],
                    'oficina' => $fila['oficina'],

                    'idedificio' => $fila['idedificio'],
                    'edificio' => $fila['edificio'],
                    'direccion_calle' => $fila['direccion_calle'],
                    'direccion_altura' => $fila['direccion_altura'],
                    'direccion' => $fila['direccion'],

                    'idempleado' => $fila['idempleado'],
                    'responsable' => $fila['responsable'],

                    'cpu' => null
                ];
            }

            /*
             * Si el inventario tiene información de CPU,
             * la agregamos una sola vez.
             */
            if ($fila['idcpu'] !== null) {

                if ($equipos[$idInventario]['cpu'] === null) {

                    $equipos[$idInventario]['cpu'] = [
                        'idcpu' => $fila['idcpu'],
                        'ram_gb' => $fila['total_ram_gb'],
                        'disco_gb' => $fila['total_disco_gb'],

                        'motherboard' => [
                            'idarticulo' => $fila['motherboard_idarticulo'],
                            'modelo' => $fila['motherboard_modelo'],
                            'descripcion' => $fila['motherboard_descripcion'],
                            'marca' => $fila['motherboard_marca']
                        ],

                        'micro' => [
                            'idarticulo' => $fila['micro_idarticulo'],
                            'modelo' => $fila['micro_modelo'],
                            'descripcion' => $fila['micro_descripcion'],
                            'marca' => $fila['micro_marca']
                        ],

                        'fuente' => [
                            'idarticulo' => $fila['fuente_idarticulo'],
                            'modelo' => $fila['fuente_modelo'],
                            'descripcion' => $fila['fuente_descripcion'],
                            'marca' => $fila['fuente_marca']
                        ],

                        'componentes' => []
                    ];
                }

                /*
                 * Cada fila puede representar un componente
                 * diferente del mismo CPU.
                 */
                if ($fila['id_componente'] !== null) {

                    $equipos[$idInventario]['cpu']['componentes'][] = [
                        'id' => $fila['id_componente'],
                        'idarticulo' => $fila['componente_idarticulo'],
                        'modelo' => $fila['componente_modelo'],
                        'descripcion' => $fila['componente_descripcion'],
                        'tipo' => $fila['componente_tipo'],
                        'marca' => $fila['componente_marca']
                    ];
                }
            }
        }

        // Sacamos las claves asociativas y devolvemos un array normal
        return array_values($equipos);
    }
}