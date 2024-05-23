<?php
require_once '../config/db.php';

//variables
$IdContacto = $_POST['id_contacto'];
$Mail = $_POST['mail_contacto'];
$Telefono = $_POST['telefono_contacto'];
$Legajo = $_POST['legajo_contacto'];
$IdDispositivo = $_POST['id_dispositivo_contacto'];
$IdPersona = $_POST['id_persona'];
$Activo = $_POST['activo'];

$sql = "UPDATE mds_org_contacto SET mail = '$Mail', telefono = '$Telefono', legajo = $Legajo, iddispositivo = $IdDispositivo,idpersona= $IdPersona, activo = $Activo WHERE idcontacto = $IdContacto";
EjecutarConsulta($sql);

//listo el pollo arma el array con el anuncio, id y descripcion para preparar el formulario del otro lado
$resultado = array("anuncio"=>"Actualizado");
    
echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 
 
function EjecutarConsulta($sql)
    {
        $dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($sql);
		$dbh->Cerrar();
		$dbh = NULL;
    }

