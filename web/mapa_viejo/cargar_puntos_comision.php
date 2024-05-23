<?php
function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

include 'includes/bd.php';

// Select all the rows in the markers table
$sql = "SELECT c.Descripcion capa, i.Descripcion detalle, i.nombre,p.latitud,p.longitud,c.Icono, case when idCapaPadre is null then c.idCapa else idCapaPadre end elpadre
        FROM CDD_GIS_punto p,CDD_GIS_capa_item i,CDD_GIS_capa c
        WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.tipo=1 and c.Activo='True' and i.idCapa=32
        ORDER BY c.idCapa;";

header("Content-type: text/xml");

// Start XML file, echo parent node
$contenido= "<markers>";

// Iterate through the rows, printing XML nodes for each
foreach ($conexion->query($sql) as $fila) {
  // ADD TO XML DOCUMENT NODE
  $contenido.= '<marker ';
  $contenido.= 'titulo="' . trim(parseToXML($fila['nombre'])) . '" ';
  $contenido.= 'detalle="' . trim(parseToXML($fila['detalle'])). '" ';
  $contenido.= 'lat="'.$fila['latitud'].'" ';
  $contenido.= 'lng="'.$fila['longitud'].'" ';
  $contenido.= 'tipo="'.$fila['capa'].'" ';
  $contenido.= 'icon="'.trim($fila['Icono']).'" ';
  $contenido.= 'padre="'.trim($fila['elpadre']).'" ';
  $contenido.= '/>';
}
// echo '</markers>';
// End XML file


// Select all the rows in the polygon table
/*$sql = "SELECT c.Descripcion capa, i.idCapaItem, i.Descripcion nombre,p.latitud,p.longitud,c.Icono 
        FROM gis_area p,gis_capa_item i,gis_capa c
        WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa $ciudad and c.tipo=2";*/



// End XML file
$contenido.= '</markers>';

$conexion->query("insert into debug values('$contenido')");

echo $contenido;
?>