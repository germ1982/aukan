<?php 
	require_once '../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "SELECT DISTINCT sds_com_configuracion.descripcion as descripcion,sds_gis_item_tematica.idtematica as idconfiguracion FROM sds_gis_item_tematica INNER JOIN sds_com_configuracion INNER JOIN sds_gis_capa_item 
    WHERE sds_gis_item_tematica.iditem = sds_gis_capa_item.idcapaitem 
    AND sds_gis_capa_item.activo = 1 
    AND sds_gis_item_tematica.idtematica = sds_com_configuracion.idconfiguracion
	";
    //85 corresponde a gis_capa_item_tematica
	$result = $dbh->Select($sql);

	$tematicas='            
                <a href="#" class="gradientmenu" onclick="menu_tematica(this);" data-value="todos"><i class="icon-map-marker" style="font-size:20px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i><p style="font-size:20px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i><p style="font-size:12px;color:lightblue;text-shadow:2px 2px 4px #000000;">Todos</p></a>
            ';
    $arr_temat = [];
    $tem=null;
	while ($fila = $dbh->Registro()){		
        $desc_tematica=$fila['descripcion'];
        $id_tematica = $fila['idconfiguracion'];
        
        if($id_tematica!=$tem){
            $tematicas.= '<a href="#" class="gradientmenu"  onclick="menu_tematica(this);"  data-value="'. $id_tematica  .'" id="tematica_seleccionada"  ><i class="icon-layers" style="font-size:20px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i><p style="font-size:12px;color:lightblue;text-shadow:2px 2px 4px #000000;"> '.$desc_tematica.'</p></a>';
            $tem = $id_tematica;
        }
	}
	$contenido='';
	$dbh->Cerrar();
    return $tematicas;
    ?>

<?php /*
	require_once '../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "select DISTINCT idtematica,descripcion,idconfiguracion
    from sds_com_configuracion inner join sds_gis_item_tematica 
    where idconfiguraciontipo = 85
    and sds_com_configuracion.idconfiguracion=sds_gis_item_tematica.idtematica
			";
    //85 corresponde a gis_capa_item_tematica
	$result = $dbh->Select($sql);

	$tematicas='

            <ul>
            <li>
                <a href="#" class="gradientmenu" onclick="menu_tematica(this);" data-value="todos"><i class="icon-map-marker"></i></a>
            </li>';
    $arr_temat = [];
    $tem=null;
	while ($fila = $dbh->Registro()){		
        $desc_tematica=$fila['descripcion'];
        $id_tematica = $fila['idconfiguracion'];

        if($id_tematica!=$tem){
            $tematicas.= '
            <li>
                <a href="#" class="gradientmenu"  onclick="menu_tematica(this);"  data-value="'. $id_tematica  .'" id="tematica_seleccionada"  ><i class="icon-layers"></i><p> '.$desc_tematica.'</p></a>
            </li>
            ';
        $tem = $id_tematica;
        }
       

	}
	$contenido='</ul>';

	$dbh->Cerrar();
    
    return $tematicas;
    */
    ?>
