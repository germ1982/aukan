<?php

require_once '../config/db.php';
$cod_ext = $_GET['entidad_id'];
$conexion = new BaseDatos();
$sql = "select tipo.idtipo,descripcion,ifnull(sum(cantidad),0) total,
count(ent.identrega) entregas
from sds_ent_tipo tipo
left join sds_ent_entrega ent on ent.idtipo=tipo.idtipo
left join mds_seg_usuario usr on usr.idusuario=ent.idusuario
where tipo.activo=1 and usr.externo=$cod_ext 
group by tipo.idtipo";
$conexion->Iniciar();
$entregas_entidad = array();
if ($conexion->Ejecutar($sql) && $conexion->Cantidad() > 0) {
    while ($fila = $conexion->Registro()) {
        $ent_ent = array(
            "cod_tipo" => $fila['idtipo'],
            "descripcion" => $fila['descripcion'],
            "entregas" => $fila['entregas'],
            "total" => $fila['total']
        );
        $entregas_entidad[] = $ent_ent;
    }
}
$conexion->Cerrar();
print json_encode($entregas_entidad);
