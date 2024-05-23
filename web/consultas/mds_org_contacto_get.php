<?php
/*  
    Este php trae todos los datos de un contacto de mds_org_contacto.
    los hace por el id del contacto o por el id de la persona.     
    si es por id del contacto, debe venir el numro como parametro y el id de la persona en 0.
    si es por el id de la persona, debe venir el numro como parametro y el id de contacto en 0.
    En base a eso define que consulta hacer.
    Si contacto no existe devolvera todos los datos vacios o en 0.
*/
require_once '../config/db.php';
require_once '../AutoSolicitud/Lib/FuncionesComunes.php';

$parametro_id_persona = $_POST['parametro_id_persona'];
$parametro_id_contacto = $_POST['parametro_id_contacto'];

$idcontacto=0;
$idpersona = 0;
$mail = "";
$telefono = "";
$legajo = 0;
$iddispositivo = 0;
$dispositivo = "";
$activo = 0;


if ($parametro_id_persona>0)
    {
        $sql = "SELECT * FROM mds_org_contacto WHERE idpersona = $parametro_id_persona";
    }
if ($parametro_id_contacto>0)
    {
        $sql = "SELECT * FROM mds_org_contacto WHERE idcontacto = $parametro_id_contacto";
    }

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
            $idcontacto= $result['idcontacto'];
            $idpersona = $result['idpersona'];
            $mail = $result['mail'];
            $telefono = $result['telefono'];
            $legajo = $result['legajo'];
            $iddispositivo = $result['iddispositivo'];
            $dispositivo = getDatoPorId('mds_org_dispositivo', 'iddispositivo', 'descripcion', $iddispositivo);
            $activo = $result['activo'];
        }
    }	
 
$dbh->Cerrar();
$dbh = NULL;

$resultado = array("idcontacto"=>$idcontacto,
                    "idpersona"=>$idpersona,
                    "mail"=>$mail,
                    "telefono"=>$telefono,
                    "legajo"=>$legajo,
                    "iddispositivo"=>$iddispositivo,
                    "dispositivo"=>$dispositivo,
                    "activo"=>$activo);
                      
echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 


