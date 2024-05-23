<?php
require_once '../config/db.php';

//variables
$Mail = $_POST['mail_contacto'];
$Telefono = $_POST['telefono_contacto'];
$Legajo = $_POST['legajo_contacto'];
$IdDispositivo = $_POST['id_dispositivo_contacto'];
$IdPersona = $_POST['id_persona'];
$Activo = $_POST['activo'];

$sql = "insert into mds_org_contacto (mail,telefono,legajo,iddispositivo,activo,idpersona) values('$Mail','$Telefono',$Legajo,$IdDispositivo,$Activo,$IdPersona)";
GuardarContacto($sql);

//Una vez guardado recupera la id del nuevo registro reutilizando la consulta y funcion de verificarexistencia
$sql = "select * from mds_org_contacto where idpersona='$IdPersona'";
$id_contacto = GetId($sql);

//listo el pollo arma el array con el anuncio, id y descripcion para preparar el formulario del otro lado
$resultado = array("anuncio"=>"Guardado","id"=>"$id_contacto");
    

echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 
 
function GuardarContacto($sql)
    {
        $dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($sql);
		$dbh->Cerrar();
		$dbh = NULL;
    }

function GetId($sql)
    {
        $id_contacto = 0;
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
                    $id_contacto = $result['idcontacto'];
                }
            }	
        $dbh->Cerrar();
        $dbh = NULL;
        return $id_contacto;
    }

