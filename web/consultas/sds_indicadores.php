<?php

require_once '../config/db.php';
$anio = $_GET['anio'];
//0:Entregas 1:SST
$tipo_indic = $_GET['tipo'];

$conexion = new BaseDatos();

$sql = $tipo_indic == 0 ? "select 'Entregas' detalle,ifnull(sum(cantidad),0) total,
count(ent.identrega) cantidad,(select ifnull(sum(cantidad),0) total
from sds_ent_entrega ent
where ent.idtipo in (7,8) and YEAR(fecha_hora)=$anio) alimentos
from sds_ent_entrega ent
where YEAR(fecha_hora)=$anio"
    :
    "select 'Subsidio por Desempleo' detalle,sum(ifnull(CAST(monto AS DECIMAL),0)) total,count(iddesempleo) cantidad
from mds_por_desempleo
where year(fecha)=$anio
union
select 'Subsidio Familia' detalle,sum(ifnull(CAST(importe AS DECIMAL),0)) total,count(idfamilia) cantidad
from mds_por_familia
where anio=$anio
union
select 'Subsidio Social Transitorio' detalle,sum(CAST(if(monto!='',monto,0) AS DECIMAL)) total,count(id) cantidad
from mds_por_sst
where anio=$anio";
$conexion->Iniciar();
$indicadores = array();
if ($conexion->Ejecutar($sql) && $conexion->Cantidad() > 0) {
    while ($fila = $conexion->Registro()) {
        $indicador = array(
            "detalle" => $fila['detalle'],
            "total" => $fila['total'],
            "cantidad" => $fila['cantidad'],
            "alimentos" => isset($fila['alimentos']) ? $fila['alimentos'] : 0
        );
        $indicadores[] = $indicador;
    }
}
$conexion->Cerrar();
print json_encode($indicadores);
