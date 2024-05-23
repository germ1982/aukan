<?php
include './includes/bd.php';

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Select all the rows in the markers table
$sql = "SELECT i.Nombre capa, i.idCapaItem, i.Descripcion nombre,a.latitud,a.longitud,i.Color 
        FROM CDD_GIS_Area a,CDD_GIS_Capa_Item i,CDD_GIS_Capa c
        WHERE a.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.tipo=2 and c.Activo='True'";

header("Content-type: text/xml");

// Start XML file, echo parent node
$contenido= "<markers>";

// Iterate through the rows, printing XML nodes for each
foreach ($conexion->query($sql) as $fila) {
  // ADD TO XML DOCUMENT NODE
  $contenido.= '<marker ';
  $contenido.= 'id="' . trim(parseToXML($fila['idCapaItem'])) . '" ';
  $contenido.= 'name="' . trim(parseToXML($fila['capa'])) . '" ';
  //echo 'name="' . $ciudad . '" ';
  $contenido.= 'address="' . trim(parseToXML($fila['nombre'])). '" ';
  $contenido.= 'lat="' . $fila['latitud'] . '" ';
  $contenido.= 'lng="' . $fila['longitud'] . '" ';
  $contenido.= 'type="restaurant" ';
  $contenido.= 'icon="'.trim($fila['Color']).'" ';
  $contenido.= '/>';
}

// End XML file
$contenido.= '</markers>';

//$conexion->query("insert into debug values('$contenido')");

echo $contenido;
?>