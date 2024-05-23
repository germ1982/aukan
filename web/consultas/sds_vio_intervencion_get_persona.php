<?php


$idpersona = $_POST['id_persona'];

require_once '../config/db.php';

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Select("SELECT nombre, apellido, fecha_nacimiento, genero, nacionalidad, telefono, domicilio, PV.idlocalidad FROM sds_com_persona as P " .
    "LEFT JOIN sds_vio_persona as PV on P.idpersona = PV.idpersona WHERE P.idpersona = " . $idpersona);
print(json_encode($dbh->Registro()));
$dbh->Cerrar();
