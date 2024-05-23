<?php
    include 'includes/bd.php';
    $sql = "SELECT Abreviatura,Icono, Descripcion,idCapa, case when idCapaPadre is null then idCapa else idCapaPadre end elpadre
            FROM CDD_GIS_Capa
            WHERE idCapaPadre is null and Tipo=1 and Activo='True' and idCapa not between 13 and 27;";
    $contenido= 
        '<div align="center" class="col-md-1 col-sm-1" style="background-color: rgba(255,255,255,0.7);padding-bottom: 0.5em;padding-top: 0.5em;padding: 0.5em 0.5em;position: fixed;top: 50px;left: 0px;cursor: pointer;">Menu<br><form id="capas">';
    foreach ($conexion->query($sql) as $fila) {
        $img=$fila['Abreviatura']."_img";
        $img_q="'".$fila['Abreviatura']."_img'";
        $check=$fila['Abreviatura']."_check";
        $check_q="'".$fila['Abreviatura']."_check'";
        $imagen=$fila['Icono'];
        $imagen_q="'".$fila['Icono']."'";
        $tipo=$fila['Descripcion'];
        $tipo_q="'".$fila['elpadre']."'";
        $extension=substr($imagen,-4);
        $imagen_sin_ext=substr($imagen, 0, -4);
        //$imagen_on=$imagen_sin_ext.'-off'.$extension;
        //$checked='';
        //if ($fila['idCapa']==7){
            $imagen_on=$imagen_sin_ext.'-on'.$extension;
            $checked=' checked="checked" ';
        //}
        $contenido.='
            <input id="'.$check.'" name="'.$tipo.'" type="checkbox" '.$checked.' hidden="true">
            <img title="'.$fila['Descripcion'].'" style="padding-bottom: 0.1em;padding-top: 0.1em;padding: 0.1em 0.1em;" id="'.$img.'" src="'.$imagen_on.'" onclick="cambiar_icono('.$img_q.','.$check_q.','.$imagen_q.','.$tipo_q.');">';
    }
    $contenido.='</form></div>';
    /*$contenido.='<div align="left" class="col-md-1 col-sm-1" style="padding-bottom: 0.5em;padding-top: 0.5em;padding: 0.5em 0.5em;position: fixed;bottom: 20px;left: 0px;cursor: pointer;"><button style="background:transparent;
    
    outline:none;
    display:block;
    cursor:pointer;" onclick="modalReferencias();">Referencias</button></div>'*/;
    echo $contenido;
