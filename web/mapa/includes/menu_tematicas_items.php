<?php
	require_once '../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "select * , sds_gis_capa.descripcion as nombre_capa,sds_gis_capa_item.descripcion as nombre_item, sds_com_configuracion.descripcion as nombre_tematica
            from sds_gis_item_tematica inner join sds_gis_capa_item inner join sds_gis_capa inner join sds_com_configuracion
            
			where sds_gis_item_tematica.iditem = sds_gis_capa_item.idcapaitem
            and sds_gis_capa.idcapa = sds_gis_capa_item.idcapa
            and sds_gis_item_tematica.idtematica = sds_com_configuracion.idconfiguracion
			and sds_gis_capa_item.activo = 1
			and sds_gis_capa.activo = 1
            order by sds_gis_capa_item.idcapaitem
			";
	$result = $dbh->Select($sql);
	$array_items_tem=[];

	while ($fila = $dbh->Registro()){		
        array_push($array_items_tem,$fila);
	}

	$dbh->Cerrar();
    
    return $array_items_tem;
    ?>
