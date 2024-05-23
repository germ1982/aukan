<?php
    header("Content-type: text/xml");
    function parseToXML($htmlStr)
    {
        $xmlStr=str_replace('<','&lt;',$htmlStr);
        $xmlStr=str_replace('>','&gt;',$xmlStr);
        $xmlStr=str_replace('"','&quot;',$xmlStr);
        $xmlStr=str_replace("'",'&#39;',$xmlStr);
        $xmlStr=str_replace("&",'&amp;',$xmlStr);
        return $xmlStr;
    }
	require_once '../config/db.php';
    $dbh = new BaseDatos();
	$dbh->Iniciar();
	$sql = "
	select * , sds_gis_capa.descripcion as nombre_capa,sds_gis_capa_item.descripcion as nombre_item, sds_gis_capa_item.idcapa as id_capa
	from sds_gis_capa_item inner join sds_gis_capa
	where  sds_gis_capa.idcapa = sds_gis_capa_item.idcapa 
	and sds_gis_capa_item.activo = 1
	and sds_gis_capa.activo = 1
	order by sds_gis_capa_item.idcapaitem";
	$result = $dbh->Select($sql);
	$iditem=0;
	$contenido= "<markers>";
	while ($fila = $dbh->Registro()){	 
		$descripcion=trim(parseToXML($fila['nombre_item']));
		$detalle=trim(parseToXML($fila['detalle']));
		$direccion=trim(parseToXML($fila['direccion']));

		if($fila['id_capa'] >= 12 ){
			$icon = 'icon="default.png" ';
		}else{
			$icon = 'icon="'.$fila['id_capa'].'-'.$fila['estado'].'.png" ';
		}

		$coordenadas = json_decode($fila['coleccion_coordenadas'],true);
		$stringcoordenadas = htmlspecialchars(json_encode($coordenadas));

		$contenido.= '<marker ';
		$contenido.= 'orden="'.$fila['idcapaitem'].'" ';
		$contenido.= 'iditem="'.$fila['idcapaitem'].'" ';
		$contenido.= 'titulo="'.$descripcion.'" ';
		$contenido.= 'detalle="'.$detalle.'" ';
		$contenido.= 'direccion="'.$direccion.'" ';
		$contenido.= 'lat="'.$fila['latitud'].'" ';
		$contenido.= 'lng="'.$fila['longitud'].'" ';
		$contenido.= 'tipo="'.$fila['id_capa'].'" ';
		$contenido.= 'tipo_mapa="' . trim(parseToXML($fila['tipo'])). '" ';

		$contenido.= 'stringcoordenadas="'.$stringcoordenadas.'" ';

		$contenido.= $icon;
		$contenido.= 'sitio="https://mindesarrolloytrabajo.neuquen.gob.ar/" ';
		$contenido.= '/>';
		$iditem = $iditem + 1;
	}
	$dbh->Cerrar();


// End XML file
$contenido.= '</markers>';

echo $contenido;

?>