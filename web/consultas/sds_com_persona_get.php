<?php
/*  
    Este php trae todos los datos de una persona de sds_com_persona.
    los hace por el id o por el documento.     
    si es por id, debe venir el numro como parametro y el documento en 0.
    si es por documento, debe venir el numro como parametro y el id en 0.
    En base a eso define que consulta hacer.
    Si la persona no existe devolvera todos los datos vacios o en 0.
*/
require_once '../config/db.php';
require_once '../AutoSolicitud/lib/FuncionesComunes.php';

$parametro_id_persona = $_POST['parametro_id_persona'];
$parametro_documento_persona = $_POST['parametro_documento_persona'];

$id=0;
$documento = 0;
$id_tipo_documento = 0;
$tipo_documento = "";
$id_nacionalidad = 0;
$nacionalidad = "";
$id_genero = 0;
$genero = "";
$fecha_nacimiento = "";
$nombre = "";
$apellido = "";
$id_padre = 0;

if ($parametro_id_persona>0)
    {
        $sql = "SELECT * FROM sds_com_persona WHERE idpersona = $parametro_id_persona";
    }
if ($parametro_documento_persona>0)
    {
        $sql = "SELECT * FROM sds_com_persona WHERE documento = $parametro_documento_persona";
    }

$dbh = new BaseDatos();
$dbh->Iniciar();
//$sql = "SELECT * FROM sds_com_persona WHERE idpersona = $id_persona";

$result = $dbh->Select($sql);
if (!$result) 
    {
        echo "<p>Error en la consulta.</p>"; 
    }
else 
    {
        while ($result = $dbh->Registro())
        {
            $id = $result['idpersona'];
            $documento = $result['documento'];
            $id_tipo_documento = $result['documento_tipo'];
            $tipo_documento = getDatoPorId('sds_com_configuracion', 'idconfiguracion', 'descripcion', $id_tipo_documento);
            $id_nacionalidad = $result['nacionalidad'];
            $nacionalidad = getDatoPorId('sds_com_configuracion', 'idconfiguracion', 'descripcion', $id_nacionalidad);
            $id_genero = $result['genero'];
            $genero = getDatoPorId('sds_com_configuracion', 'idconfiguracion', 'descripcion', $id_genero);
            $fecha_nacimiento = $result['fecha_nacimiento'];
            $nombre = $result['nombre'];
            $apellido = $result['apellido'];
            $id_padre = $result['padre'];
        }
    }	
 
$dbh->Cerrar();
$dbh = NULL;

$resultado = array("id"=>$id,
                    "documento"=>$documento,
                    "id_tipo_documento"=>$id_tipo_documento,
                    "tipo_documento"=>$tipo_documento,
                    "id_nacionalidad"=>$id_nacionalidad,
                    "nacionalidad"=>$nacionalidad,
                    "id_genero"=>$id_genero,
                    "genero"=>$genero,
                    "fecha_nacimiento"=>$fecha_nacimiento,
                    "nombre"=>$nombre,
                    "apellido"=>"$apellido",
                    "id_padre"=>$id_padre);
                      
echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 




