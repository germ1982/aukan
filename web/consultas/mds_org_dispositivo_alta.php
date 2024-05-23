<?php
require_once '../config/db.php';

//variables
$descripcion = $_POST['descripcion'];
$idorganismo = $_POST['idorganismo'];
$idcapaitem = $_POST['idcapaitem'];
$activo = $_POST['activo'];

$sql = "insert into mds_org_dispositivo (descripcion,idorganismo,activo,idcapaitem) values('$descripcion',$idorganismo,$activo,$idcapaitem)";
EjecutarConsulta($sql);

//Una vez guardado recupera la id del nuevo registro reutilizando la consulta y funcion de verificarexistencia
$sql = "select * from mds_org_dispositivo where descripcion='$descripcion'";
$id_dispositivo = GetId($sql);

//listo el pollo arma el array con el anuncio, id y descripcion para preparar el formulario del otro lado
$resultado = array("anuncio"=>"Guardado","id"=>"$id_dispositivo","descripcion"=>"$descripcion","idorganismo"=>"$idorganismo");
    

echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 
 
function EjecutarConsulta($sql)
    {
        $dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($sql);
		$dbh->Cerrar();
		$dbh = NULL;
    }

function GetId($sql)
    {
        $id_dispositivo = 0;
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
                    $id_dispositivo = $result['iddispositivo'];
                }
            }	
        $dbh->Cerrar();
        $dbh = NULL;
        return $id_dispositivo;
    }

