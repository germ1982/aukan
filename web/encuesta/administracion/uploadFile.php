<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('post_max_size', '256M');
ini_set('upload_max_filesize', '128M');
ini_set('max_input_vars', '1800');
ini_set('max_execution_time', '400');
ini_set('max_input_time', '400');
$id_tipo_encuesta = $_REQUEST['id_tipo_encuesta'];
$header = $_FILES['header'];
$tipo_header = $_FILES['header']['type'];
$footer = $_FILES['footer'];
$tipo_footer = $_FILES['footer']['type'];

$ruta_destino = '../../img/uploads_encuesta/';
$rand = rand();
$archivos_delete = "";
$name_header = "";
$name_footer = "";
$origen_header = $_FILES['header']['tmp_name'];
$origen_footer = $_FILES['footer']['tmp_name'];
$destino_header = $ruta_destino .$rand."_".str_replace(" ", "_", str_replace(":", "_",$_FILES['header']['name']));
$destino_footer = $ruta_destino .$rand."_".str_replace(" ", "_", str_replace(":", "_",$_FILES['footer']['name']));
if (!@move_uploaded_file($origen_header, $destino_header)) {
    echo "<br>No se ha podido mover el archivo: " . $_FILES['header']['error'] . '_' . str_replace(" ", "_", str_replace(":", "_", $_FILES['header']['name'])) . "<br>";
}else{
    $name_header = $rand."_".str_replace(" ", "_", str_replace(":", "_",$_FILES['header']['name']));
}
if (!@move_uploaded_file($origen_footer, $destino_footer)) {
    echo "<br>No se ha podido mover el archivo: " . $_FILES['footer']['error'] . '_' . str_replace(" ", "_", str_replace(":", "_", $_FILES['footer']['name'])) . "<br>";
}else{
    $name_footer = $rand."_".str_replace(" ", "_", str_replace(":", "_",$_FILES['footer']['name']));
}

$bd = new baseDatos();
$bd->Conectarse();
$bd->select("UPDATE mds_encuesta_tipo SET header='$name_header',footer='$name_footer' WHERE id_tipo = $id_tipo_encuesta");
echo "<script>
        history.back();
        </script>";
?>