<?php

require_once '../config/db.php';
$anio = $_GET['anio'];
$conexion = new BaseDatos();
$sql = "select idorganismoexterno cod_ext,descripcion,ifnull(sum(cantidad),0) total,
count(ent.identrega) entregas,sum(if(idtipo=7 or idtipo=8,cantidad,0)) alimentos
from mds_org_organismo_externo ext
left join mds_seg_usuario usr on usr.externo=ext.idorganismoexterno
left join sds_ent_entrega ent on ent.idusuario=usr.idusuario
where ext.activo=1
and (YEAR(fecha_hora)=$anio or ent.idusuario is null)
group by cod_ext";
$conexion->Iniciar();
$entregas_entidad = array();
if ($conexion->Ejecutar($sql) && $conexion->Cantidad() > 0) {
    while ($fila = $conexion->Registro()) {
        $ent_ent = array(
            "cod_ext" => $fila['cod_ext'],
            "descripcion" => $fila['descripcion'],
            "entregas" => $fila['entregas'],
            "total" => $fila['total'],
            "alimentos" => $fila['alimentos']
        );
        $entregas_entidad[] = $ent_ent;
    }
}
$conexion->Cerrar();
print json_encode($entregas_entidad);
