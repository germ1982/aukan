<?php
require_once '../config/db.php';

//variables
$documento = $_POST['par_documento'];
$tipo_documento = $_POST['par_tipo_documento'];
$nacionalidad = $_POST['par_nacionalidad'];
$genero = $_POST['par_genero'];
$fecha_nacimiento = $_POST['par_fecha_nacimiento'];
$fecha_nacimiento = ArmarDateParaMySql($fecha_nacimiento);
$nombre = $_POST['par_nombre'];
$apellido = $_POST['par_apellido'];
$padre = $_POST['par_padre'];
$conviviente = $_POST['par_conviviente'];

$sql = "insert into sds_com_persona (documento, documento_tipo, nacionalidad, genero, fecha_nacimiento, nombre, apellido, padre, conviviente) values($documento,$tipo_documento,$nacionalidad,$genero,'$fecha_nacimiento','$nombre','$apellido',$padre,$conviviente)";
$sql1= $sql;
GuardarPersona($sql);

//Una vez guardado recupera la id del nuevo registro reutilizando la consulta y funcion de verificarexistencia
$sql = "select * from sds_com_persona where documento=$documento";
$id = GetId($sql);

//listo el pollo arma el array con el anuncio, id y descripcion para preparar el formulario del otro lado
$resultado = array("id"=>"$id","sql"=>"$sql1");
    

echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 
 
function GuardarPersona($sql)
    {
        $dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($sql);
		$dbh->Cerrar();
		$dbh = NULL;
    }

function GetId($sql)
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
    }

function ArmarDateParaMySql($Fecha)
	{
		$anio = substr($Fecha, 6,4);
		$mes  = substr($Fecha, 3,2);
		$dia = substr($Fecha, 0,2);
		$DT = "$anio-$mes-$dia";
		return $DT;
	}

