<?php 
    $ruta=$_SERVER['DOCUMENT_ROOT'];
    if (file_exists($ruta.'/gis')){
        $ruta=$ruta.'/gis';
    }
    $contenido='<option value="">Seleccione Localidad...</option>';
    include $ruta.'/includes/bd.php';
    $seleccionado=$_GET['padre'];
    $selected='';
    $sql="SELECT c.Icono, c.idCapa, i.idCapaItem, i.Descripcion Descripcion, i.Nombre,p.Latitud,p.Longitud, c.Descripcion Capa
          FROM gis_punto p,gis_capa_item i,gis_capa c
          WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.idCapa=7";
    foreach ($conexion->query($sql) as $fila) {
        $item=$fila['Nombre'];
        $id=$fila['idCapaItem'];
        if($seleccionado==$id){$selected='selected ';};
        $contenido.='<option '.$selected.'value="'.$id.'">'.$item.'</option>';
    }
    echo $contenido;
?>