<?php
	require_once '../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "select *
            from sds_gis_capa
			where activo
            order by descripcion";
	$result = $dbh->Select($sql);
	$contenido='<div id="mySidenav" class="sidenav">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <div class="row">
                        <div style="width: 90%;font-weight:bold; color: white; position:absolute; left:10px; top:50px;">CATEGORÍA</div>
                        <div style="width: 10%; color: white; position:absolute; left:190px; top:50px;"><input id="check0" type="checkbox" value="todos" onchange="cambiar_todo(this.id);" style="transform: scale(1.5);" checked></div>
                    </div>';
	$top=77;
	while ($fila = $dbh->Registro()){		
		$desc="'".$fila['descripcion']."'";
        $desc_p=$fila['descripcion'];
        $idcapa=$fila['idcapa'];
        $contenido.='<div class="row">
                        <div style="width: 90%; color: white; position:absolute; left:20px; top:'.$top.'px;">'.$desc_p.'</div>
                        <div style="width: 10%; color: white; position:absolute; left:190px; top:'.$top.'px;"><input id="check'.$idcapa.'" type="checkbox" value="'.$idcapa.'" onclick="cambiar_icono(null,this.id, null,'.$idcapa.');" style="transform: scale(1.5);" checked></div>
                    </div>';
        $top=$top+27;
	}
	$dbh->Cerrar();
	
    $contenido.='</div>';
    echo $contenido;