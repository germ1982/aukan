<?php

$idcapaitem = $_GET['idcapaitem'];

require_once '../config/db.php';

$dbh = new BaseDatos();
$dbh->Iniciar();
$sql = "select *
		from sds_gis_capa_item
		where idcapaitem=$idcapaitem 
		order by idcapa,descripcion";
$dbh->Select($sql);
print(json_encode($dbh->Registro()));
$dbh->Cerrar();
