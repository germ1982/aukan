<?php


$dni_persona = $_POST['dni_persona'];

require_once '../config/db.php';

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Select("select * from sds_com_persona where documento = " . $dni_persona);
print(json_encode($dbh->Registro()));
$dbh->Cerrar();
