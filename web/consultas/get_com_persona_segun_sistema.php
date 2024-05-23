<?php

$id_persona = $_POST['id_persona'];
$tabla_sistema = $_POST['tabla_sistema'];


require_once '../config/db.php';

$dbh = new BaseDatos();
$dbh->Iniciar();
$sql = "SELECT * FROM sds_com_persona WHERE idpersona = $id_persona";

$result = $dbh->Select($sql);
if (!$result) 
    {
        echo "<p>Error en la consulta.</p>"; 
    }
else 
    {
        while ($result = $dbh->Registro())
        {
            $nombre = $result['nombre'];
            $apellido = $result['apellido'];
            $nacionalidad = $result['nacionalidad'];
            $genero = $result['genero'];
            $fecha_nacimiento = $result['fecha_nacimiento'];
        }
    }	

    $telefono = "";
    $mail = "";
    $legajo = "";
    $id_dispositivo = "";

  if($tabla_sistema == 'mds_cap_persona')
    {
        //$telefono = "";
        //$mail = "";
        $sql = "SELECT * FROM mds_cap_persona WHERE idpersona = $id_persona order by idpersona desc limit 1";
        $result = $dbh->Select($sql);
        if (!$result) 
            {
                echo "<p>Error en la consulta.</p>"; 
            }
        else 
            {
                while ($result = $dbh->Registro())
                {
                    $telefono = $result['telefono'];
                    $mail = $result['mail'];
                }
            }	
    }  

    if($tabla_sistema == 'mds_org_contacto')
    {
        //$telefono = "";
        //$mail = "";
        //$legajo = "";
        //$id_dispositivo = "";
        $sql = "SELECT * FROM mds_org_contacto WHERE idpersona = $id_persona";
        $result = $dbh->Select($sql);
        if (!$result) 
            {
                echo "<p>Error en la consulta.</p>"; 
            }
        else 
            {
                while ($result = $dbh->Registro())
                {
                    $telefono = $result['telefono'];
                    $mail = $result['mail'];
                    $legajo = $result['legajo'];
                    $id_dispositivo = $result['iddispositivo'];
                }
            }	
    }  




$dbh->Cerrar();
$dbh = NULL;

$resultado = array("nombre"=>$nombre,"apellido"=>"$apellido","fecha_nacimiento"=>$fecha_nacimiento,"nacionalidad"=>$nacionalidad,"genero"=>$genero,"telefono"=>$telefono,"mail"=>$mail,"legajo"=>$legajo,"iddispositivo"=>$id_dispositivo);
echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 




