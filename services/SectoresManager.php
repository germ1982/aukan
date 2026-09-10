<?php

namespace app\services;

use yii\db\Query;

class SectoresManager
{
    /**
     * Busca los sectores / dispositivos vinculados a una oficina específica.
     */
    public function buscarPorOficina($idoficina)
    {
        return (new Query())
            ->select([
                'od.iddispositivo',
                'od.descripcion AS sector',
                'od.idorganismo',
                'o.descripcion AS organismo',
                'eo.idoficina',
                'eo.descripcion AS oficina',
            ])
            ->from(['od' => 'organismo_dispositivo'])
            ->innerJoin(
                ['o' => 'organismo'],
                'o.idorganismo = od.idorganismo'
            )
            ->leftJoin(
                ['eo' => 'edificio_oficina'],
                'eo.idoficina = od.idoficina'
            )
            ->where([
                'od.idoficina' => $idoficina,
                'od.activo' => 1
            ])
            ->all();
    }

    /**
     * Obtiene todos los equipos e inventario asociados a un dispositivo.
     */
    public function obtenerEquiposPorDispositivo($idDispositivo)
    {
        return (new Query())
            ->select([
                'o.idorganismo',
                'o.descripcion AS organismo',

                'od.iddispositivo',
                'od.descripcion AS sector',

                'i.idinventario',
                'a.idarticulo',
                'a.modelo',
                'a.descripcion AS descripcion_articulo',
                'tipo.descripcion AS tipo_articulo',
                'marca.descripcion AS marca',

                'eo.idoficina',
                'eo.descripcion AS oficina',

                'e.idedificio',
                'e.descripcion_fija AS edificio',
                'e.direccion_calle',
                'e.direccion_altura',
                'e.direccion',

                'i.idempleado',
                "CONCAT(p.apellido, ', ', p.nombre) AS responsable",

                'ic.idcpu',
                'ic.total_ram_gb',
                'ic.total_disco_gb',

                'am.idarticulo AS motherboard_idarticulo',
                'am.modelo AS motherboard_modelo',
                'am.descripcion AS motherboard_descripcion',
                'amarca.descripcion AS motherboard_marca',

                'ami.idarticulo AS micro_idarticulo',
                'ami.modelo AS micro_modelo',
                'ami.descripcion AS micro_descripcion',
                'amimarca.descripcion AS micro_marca',

                'af.idarticulo AS fuente_idarticulo',
                'af.modelo AS fuente_modelo',
                'af.descripcion AS fuente_descripcion',
                'afmarca.descripcion AS fuente_marca',

                'icc.id AS id_componente',
                'acomp.idarticulo AS componente_idarticulo',
                'acomp.modelo AS componente_modelo',
                'acomp.descripcion AS componente_descripcion',
                'tipocomp.descripcion AS componente_tipo',
                'marcacomp.descripcion AS componente_marca',
            ])

            ->from(['od' => 'organismo_dispositivo'])

            ->innerJoin(
                ['o' => 'organismo'],
                'o.idorganismo = od.idorganismo'
            )

            ->innerJoin(
                ['i' => 'inventario'],
                'i.iddispositivo = od.iddispositivo'
            )

            ->innerJoin(
                ['a' => 'articulo'],
                'a.idarticulo = i.idarticulo'
            )

            ->leftJoin(
                ['tipo' => 'configuracion'],
                'tipo.id_configuracion = a.idtipo'
            )

            ->leftJoin(
                ['marca' => 'configuracion'],
                'marca.id_configuracion = a.idmarca'
            )

            ->leftJoin(
                ['eo' => 'edificio_oficina'],
                'eo.idoficina = od.idoficina'
            )

            ->leftJoin(
                ['e' => 'edificio'],
                'e.idedificio = eo.idedificio'
            )

            ->leftJoin(
                ['emp' => 'empleado'],
                'emp.idempleado = i.idempleado'
            )

            ->leftJoin(
                ['p' => 'personas'],
                'p.idpersona = emp.idpersona'
            )

            // Información específica del CPU
            ->leftJoin(
                ['ic' => 'inventario_cpu'],
                'ic.idinventario = i.idinventario'
            )

            ->leftJoin(
                ['am' => 'articulo'],
                'am.idarticulo = ic.idmother'
            )

            ->leftJoin(
                ['amarca' => 'configuracion'],
                'amarca.id_configuracion = am.idmarca'
            )

            ->leftJoin(
                ['ami' => 'articulo'],
                'ami.idarticulo = ic.idmicro'
            )

            ->leftJoin(
                ['amimarca' => 'configuracion'],
                'amimarca.id_configuracion = ami.idmarca'
            )

            ->leftJoin(
                ['af' => 'articulo'],
                'af.idarticulo = ic.idfuente'
            )

            ->leftJoin(
                ['afmarca' => 'configuracion'],
                'afmarca.id_configuracion = af.idmarca'
            )

            // Componentes adicionales del CPU
            ->leftJoin(
                ['icc' => 'inventario_cpu_componente'],
                'icc.idcpu = ic.idcpu'
            )

            ->leftJoin(
                ['acomp' => 'articulo'],
                'acomp.idarticulo = icc.idarticulo'
            )

            ->leftJoin(
                ['tipocomp' => 'configuracion'],
                'tipocomp.id_configuracion = icc.id_tipo_componente'
            )

            ->leftJoin(
                ['marcacomp' => 'configuracion'],
                'marcacomp.id_configuracion = acomp.idmarca'
            )

            ->where([
                'od.iddispositivo' => $idDispositivo,
                'i.activo' => 1
            ])

            ->orderBy([
                'i.idinventario' => SORT_ASC,
                'icc.id' => SORT_ASC
            ])

            ->all();
    }
}
