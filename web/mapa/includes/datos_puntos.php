<?php 
	require_once '../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "select * ,sds_gis_capa.descripcion as nombre_capa,sds_gis_capa_item.descripcion as nombre_item
			from sds_gis_capa INNER JOIN sds_gis_capa_item
			where sds_gis_capa.activo = 1 and sds_gis_capa_item.activo = 1 and sds_gis_capa.idcapa=sds_gis_capa_item.idcapa
			order by sds_gis_capa_item.descripcion
			";
	$result = $dbh->Select($sql);

	$array_puntos=[];

	while ($fila = $dbh->Registro()){		
        $idcapa=$fila['idcapa'];
		array_push($array_puntos,$fila);
	}
	$dbh->Cerrar();
    
    return $array_puntos;
	
?>





<?php /*
	require_once '../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "select *
            from sds_gis_capa
			where sds_gis_capa.activo = 1
            order by idcapa 
			";
	$result = $dbh->Select($sql);

	$dbh2 = new BaseDatos();
    $dbh2->Iniciar();

	$array_puntos=[];

	while ($fila = $dbh->Registro()){		
        $idcapa=$fila['idcapa'];
		array_push($array_puntos,$fila);

        $sql2 = 'select *
        from sds_gis_capa_item 
        where sds_gis_capa_item.idcapa = '.$idcapa.' and sds_gis_capa_item.activo = 1';
        $result2 = $dbh2->Select($sql2);

        while ($fila2 = $dbh2->Registro()){	
			array_push($array_puntos,$fila2);
        }
	}
    $dbh2->Cerrar();
	$dbh->Cerrar();
    
    return $array_puntos;
	*/
?>
