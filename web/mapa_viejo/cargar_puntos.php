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
	$sql = "select *
            from sds_gis_capa_item
			where activo
            order by idcapa,descripcion";
	$result = $dbh->Select($sql);
	$contenido= "<markers>";
	while ($fila = $dbh->Registro()){	
		$descripcion=trim(parseToXML($fila['descripcion']));
		$detalle=trim(parseToXML($fila['detalle']));
		$direccion=trim(parseToXML($fila['direccion']));
		$contenido.= '<marker ';
		$contenido.= 'titulo="'.$descripcion.'" ';
		$contenido.= 'detalle="'.$detalle.'" ';
		$contenido.= 'direccion="'.$direccion.'" ';
		$contenido.= 'lat="'.$fila['latitud'].'" ';
		$contenido.= 'lng="'.$fila['longitud'].'" ';
		$contenido.= 'tipo="'.$fila['idcapa'].'" ';
		$contenido.= 'icon="'.$fila['idcapa'].'-'.$fila['estado'].'.png" ';
		$contenido.= 'sitio="http://www.jeds.com.ar" ';
		$contenido.= '/>';
	}
	$dbh->Cerrar();


// End XML file
$contenido.= '</markers>';

echo $contenido;
?>