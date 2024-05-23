<?php 
	require_once '../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "SELECT * FROM sds_gis_item_tematica ,sds_com_configuracion WHERE sds_gis_item_tematica.idtematica = sds_com_configuracion.idconfiguracion";
	$result = $dbh->Select($sql);

	$array_item_tematicas=[];

	while ($fila = $dbh->Registro()){		
		array_push($array_item_tematicas,$fila);
	}
	$dbh->Cerrar();
    
    return $array_item_tematicas;
?>