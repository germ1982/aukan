<?php
require_once '../config/db.php'; // Ajusta la ruta según sea necesario

$db = new BaseDatos();
if ($db->Iniciar()) {
    $consulta = "SELECT * from informatica_web_sectores WHERE activo = 1 order by orden";
    $result = $db->Select($consulta);
    if ($result) {
        mostrar_sectores($result);
    }
    $db->Cerrar();
} else {
    echo "Error al conectar a la base de datos";
}


function mostrar_sectores($sectores)
{
    /* echo '<div class="row titulo_seccion" style="width: 90%; margin: 0 auto;" ;>
    <u>Institucional</u>
    </div>
    <br>'; */
    foreach ($sectores as $sector) {
        //echo $sector['nombre'] . '<br>';
        $grafico = $sector['fotos'];
        $alto_grafico = $sector['alto_foto']==0? 'auto': $sector['alto_foto'];
        $titulo = $sector['nombre'];
        $descripcion = $sector['descripcion'];
        include 'sector_tarjeta.php';
    }
}
?>

<br><br>