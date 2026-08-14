<?php

namespace app\services;

use yii\db\Query;
use yii\db\Expression;

class EquipoManager
{

    /**
     * Tipos de artículos considerados equipos para la API.
     */
    private const TIPOS_EQUIPOS = [
        110, // Monitor
        113, // Teclado
        127, // Modulo de Memoria
        128, // Memoria USB
        129, // Fuente de Alimentación
        130, // Toner
        218, // Switch
        219, // Rack
        220, // Impresora
        222, // Pc Completa
        389, // Parlante
        394, // Placa de Red
        395, // Router
        397, // Estabilizador
        446, // Disco Rigido
        479, // Reloj Biometrico
        481, // CPU
        483, // Teléfono
        485, // Notebook
        486, // Central Telefónica
        492, // Cámara Seguridad
        498, // TP-LINK
        503, // Antena de Internet Provincial
        511, // Modem
        571, // Procesador
    ];

    public function listar()
    {
        $query = (new Query())
            ->select([
                'i.idinventario AS idInventario',

                'tipo.descripcion AS tipo',
                'marca.descripcion AS marca',

                'a.modelo',
                'a.descripcion AS descripcion',

                'unidad.descripcion AS unidadMedida',

                'e.descripcion_fija AS edificio',

                'rubro.descripcion AS rubro',

                new Expression(
                    "CONCAT(p.apellido, ', ', p.nombre) AS responsable"
                ),
            ])
            ->from('inventario i')
            ->innerJoin('articulo a', 'a.idarticulo = i.idarticulo')

            ->leftJoin(
                'configuracion tipo',
                'tipo.id_configuracion = a.idtipo'
            )

            ->leftJoin(
                'configuracion marca',
                'marca.id_configuracion = a.idmarca'
            )

            ->leftJoin(
                'configuracion unidad',
                'unidad.id_configuracion = a.id_unidad_medida'
            )
            ->leftJoin(
                'organismo_dispositivo od',
                'od.iddispositivo = i.iddispositivo'
            )

            ->leftJoin(
                'edificio_oficina eo',
                'eo.idoficina = od.idoficina'
            )

            ->leftJoin(
                'edificio e',
                'e.idedificio = eo.idedificio'
            )
            ->leftJoin(
                'empleado emp',
                'emp.idempleado = i.idempleado'
            )

            ->leftJoin(
                'personas p',
                'p.idpersona = emp.idpersona'
            )
            ->leftJoin(
                'configuracion rubro',
                'rubro.id_configuracion = a.idrubro'
            )
            ->where([
                'a.idtipo' => self::TIPOS_EQUIPOS
            ])
            ->orderBy('i.idinventario');

        return $query->all();
    }
}
