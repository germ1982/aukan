<?php 
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

	$combo_disp='<select class="mi-selector" style="width: 100%;" id="item_seleccionado">
                    <option value="">ingrese:</option>';
    $cont =0;
    $datos=[];
	while ($fila = $dbh->Registro()){		
		$desc="'".$fila['descripcion']."'";
        $desc_p=$fila['descripcion'];
        $idcapa=$fila['idcapa'];

        $sql2 = 'select *
        from sds_gis_capa_item 
        where sds_gis_capa_item.idcapa = '.$idcapa.' and sds_gis_capa_item.activo = 1';
        $result2 = $dbh2->Select($sql2);
        //$combo_disp.='<option value="'.$cont.'"  >'.$desc_p.$cont. '</option>';

        while ($fila2 = $dbh2->Registro()){
            $item_id = $fila2['idcapaitem'];
            $item = $fila2['descripcion'];
            $id_item = $cont;
            $lat = $fila2['latitud'];
            $long = $fila2['longitud'];
            $combo_disp.='<option value="'.$item_id.'"  >'.$item.'</option>';
            $cont= $cont+1;
        }
      //  $cont= $cont+1;
	}
    $combo_disp.= '</select>';
    $dbh2->Cerrar();
	$dbh->Cerrar();
    
    return $combo_disp;
?>

