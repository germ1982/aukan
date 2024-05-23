<?php

require_once '../config/db.php';

$indice_sub = $_GET['indice_sub'];
$anio = $_GET['anio'];
$conexion = new BaseDatos();
$sql = $indice_sub == 0 ? "select sum(ifnull(CAST(monto AS DECIMAL),0)) total,count(iddesempleo) cantidad,month(fecha) mes
from mds_por_desempleo
where year(fecha)=$anio
group by mes" : ($indice_sub == 1 ? "select sum(ifnull(CAST(importe AS DECIMAL),0)) total,count(idfamilia) cantidad,mes
from mds_por_familia
where anio=$anio
group by mes" :
    "select sum(CAST(if(monto!='',monto,0) AS DECIMAL)) total,count(id) cantidad,mes
from mds_por_sst
where anio=$anio
group by mes");
$conexion->Iniciar();
$subsidios = array();
if ($conexion->Ejecutar($sql) && $conexion->Cantidad() > 0) {
    while ($fila = $conexion->Registro()) {
        $subs_fila = array(
            "mes" => $fila['mes'],
            "cantidad" => $fila['cantidad'],
            "total" => $fila['total']
        );
        $subsidios[] = $subs_fila;
    }
}
$conexion->Cerrar();

print json_encode($subsidios);
