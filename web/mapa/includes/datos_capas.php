<?php 
	require_once '../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "select * ,sds_gis_capa.descripcion as nombre_capa
			from sds_gis_capa INNER JOIN sds_gis_capa_item
			where sds_gis_capa.activo = 1 and sds_gis_capa_item.activo = 1 and sds_gis_capa.idcapa=sds_gis_capa_item.idcapa
			order by sds_gis_capa.descripcion
			";
	$result = $dbh->Select($sql);
	$array_capas=[];
	$idcapa=null;
	while ($fila = $dbh->Registro()){
		if($idcapa!=$fila['idcapa']){
			array_push($array_capas,$fila);
		}		
		$idcapa=$fila['idcapa'];
	}
	$dbh->Cerrar();
    
    return $array_capas;
	
?>

