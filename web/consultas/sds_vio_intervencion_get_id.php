<?php

$Dato = $_GET['dato'];
$Tabla = $_GET['tabla'];
$IdCampo = $_GET['campo_id'];
$CampoDato = $_GET['campo_dato'];

require_once '../config/db.php';

$dbh = new BaseDatos();
$dbh->Iniciar();
$sql = "select * from $Tabla where $CampoDato='$Dato'";
$dbh->Select($sql);
print(json_encode($dbh->Registro()));
$dbh->Cerrar();



/*require_once '../AutoSolicitud/Lib/FuncionesComunes.php';

$Dato = $_GET['dato'];
$Tabla = $_GET['tabla'];
$IdCampo = $_GET['campo_id'];
$CampoDato = $_GET['campo_dato'];

$id = getId($Tabla, $IdCampo, $CampoDato, $Dato)

print(json_encode($id));*/

