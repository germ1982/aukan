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

	$contenido='';
    $cont =0;

	while ($fila = $dbh->Registro()){		
		$desc="'".$fila['descripcion']."'";
        $desc_p=$fila['descripcion'];
        $idcapa=$fila['idcapa'];

        $sql2 = 'select *
        from sds_gis_capa_item 
        where sds_gis_capa_item.idcapa = '.$idcapa.' and sds_gis_capa_item.activo = 1 order by sds_gis_capa_item.idcapaitem
        ';
        $result2 = $dbh2->Select($sql2);
       

        $contenido.= '
                <nav>
                    <ul >
                        <li class="catalog" >
                            <a>
                                <input id="check'.$idcapa.'" type="checkbox" value="'.$idcapa.'" onclick="cambiar_icono_capas_todos(null,this.id, null,'.$idcapa.');" style="transform: scale(1.2);">
                                '.$desc_p.'
                            </a>
                    <ul>
                    ';
	    while ($fila2 = $dbh2->Registro()){
            $item = $fila2['descripcion'];
            $idcapaitem = $fila2['idcapaitem'];

            $id_item = $cont;
            $lat = $fila2['latitud'];
            $long = $fila2['longitud'];

             $contenido.='
                        <li>  
                            <a> 
                                <input id="check'.$idcapaitem.'" type="checkbox" value="'.$idcapaitem.'" onclick="cambiar_icono_item(null,this.id, null,'.$idcapaitem.','.$lat.','.$long.');" style="transform: scale(1);" >
                                '.$item.'
                            </a>
                        </li>

                    ';
            $cont= $cont+1;
        }

        $contenido.= '</ul>
                    </li>   
                </ul>
        </nav>';

	}
    $dbh2->Cerrar();

	$dbh->Cerrar();
    
    return $contenido;
?>
