<?php
require_once '../config/db.php';

//Vengo a guardar la nueva configuracion con las dos variables, el id del tipo de configuracion y la descripcion
$idconfiguraciontipo = $_POST['id_tipo_configuracion'];
$descripcion = $_POST['descripcion_configuracion'];

//Con las variables anteriores hago una consulta a la tabla de configuracion para ver si ese dato ya existe.
//El trabajo se realiza en la funcion VerificarExistencia a la cual le paso la consulta como parametro.
$sql = "select * from sds_com_configuracion where descripcion='$descripcion' and idconfiguraciontipo = $idconfiguraciontipo";
$id_configuracion = VerificarExistencia($sql);

//la funcion VerificarExistencia devuelve 0 si no existe o el numero de id si ya existe el dato y se trabaja en el siguiente if
if($id_configuracion>0)
    {
        //Si existe devuelve el arreglo con el anuncio de que ya existe
        //lo devuelve en array porque del otro lado esperan como respuesta un json
        $resultado = array("anuncio"=>"Ya Existe");
    }
else
    {
        //si no encontro nada va a guardar con la siguiente consulta a travez de la funcion GuardarConfiguracion
        $sql = "insert into sds_com_configuracion (idconfiguraciontipo,descripcion,activo) values($idconfiguraciontipo,'$descripcion',1)";
        GuardarConfiguracion($sql);

        //Una vez guardado recupera la id del nuevo registro reutilizando la consulta y funcion de verificarexistencia
        $sql = "select * from sds_com_configuracion where descripcion='$descripcion' and idconfiguraciontipo = $idconfiguraciontipo";
        $id_configuracion = VerificarExistencia($sql);

        //listo el pollo arma el array con el anuncio, id y descripcion para preparar el formulario del otro lado
        $resultado = array("anuncio"=>"Guardado","id"=>"$id_configuracion","descripcion"=>"$descripcion");
    }

echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 
 
function GuardarConfiguracion($sql)
    {
        $dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($sql);
		$dbh->Cerrar();
		$dbh = NULL;
    }

function VerificarExistencia($sql)
    {
        $id_configuracion = 0;
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
                    $id_configuracion = $result['idconfiguracion'];
                }
            }	
        $dbh->Cerrar();
        $dbh = NULL;
        return $id_configuracion;
    }