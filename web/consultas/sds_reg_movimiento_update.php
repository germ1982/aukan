<?php
require_once '../config/db.php';

//variables
$idmovimiento = $_POST['idmovimiento'];
$idregistro = $_POST['idregistro'];
$idusuario = $_POST['idusuario'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$fecha = ArmarDateParaMySql($fecha,$hora);
$descripcion = $_POST['descripcion'];
$tipo = $_POST['tipo']; 
$tecnico = $_POST['tecnico'];

$sql = "UPDATE sds_reg_movimiento 
        SET idregistro=$idregistro, idusuario=$idusuario, fecha='$fecha', descripcion='$descripcion', tipo=$tipo, idtecnico=$tecnico
        WHERE idmovimiento=$idmovimiento";


guardar_movimiento($sql);

/* //Una vez guardado recupera la id del nuevo registro reutilizando la consulta y funcion de verificarexistencia
$sql = "select * from sds_com_persona where documento=$documento";
$id = GetId($sql); */

//listo el pollo arma el array con el anuncio, id y descripcion para preparar el formulario del otro lado
//$resultado = array("anuncio"=>"Guardado","id"=>"$id");
$resultado = array("anuncio"=>"Guardado");

echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 
 
function guardar_movimiento($sql)
    {
        $dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($sql);
		$dbh->Cerrar();
		$dbh = NULL;
    }

/* function GetId($sql)
    {
        $id_persona = 0;
        $dbh = new BaseDatos();
        $dbh->Iniciar();

        $result = $dbh->Select($sql);
        if (!$result) 
            {
                echo "<p>Error en la consulta.</p>"; 
            }
        else 
            {
                while ($result = $dbh->Registro())
                {
                    $id_persona = $result['idpersona'];
                }
            }	
        $dbh->Cerrar();
        $dbh = NULL;
        return $id_persona;
    } */

function ArmarDateParaMySql($Fecha,$Hora)
	{
		$anio = substr($Fecha, 6,4);
		$mes  = substr($Fecha, 3,2);
		$dia = substr($Fecha, 0,2);
        $H = substr($Hora, 0,2);
        $m = substr($Hora, 3,2);
		$DT = "$anio-$mes-$dia $H:$m:00";
		return $DT;
	}

