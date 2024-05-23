<?php


$sql = $_POST['consulta_sql'];

require_once '../config/db.php';

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Select($sql);
print(json_encode($dbh->Registro()));
$dbh->Cerrar();
