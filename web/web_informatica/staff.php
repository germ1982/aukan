<?php
require_once '../config/db.php'; // Ajusta la ruta según sea necesario

$db = new BaseDatos();
if ($db->Iniciar()) {
    $consulta = "SELECT concat(p.nombre,' ',p.apellido) as nombre,
                        c.descripcion as funcion,
                        we.descripcion,
                        e.foto
                    FROM informatica_web_empleados we
                        join  empleado e on we.idempleado = e.idempleado
                        join personas p on p.idpersona = e.idpersona
                        join configuracion c on c.id_configuracion = e.funcion 
                    where we.activo = 1
                    order by we.orden";
    $result = $db->Select($consulta);
    if ($result) {
        mostrar_empleados($result);
    }
    $db->Cerrar();
} else {
    echo "Error al conectar a la base de datos";
}


function mostrar_empleados($empleados)
{
    echo '<div class="row titulo_seccion" style="width: 90%; margin: 0 auto;" ;>
    <u>Staff</u>
    </div>
    <br>';
    foreach ($empleados as $empleado) {
        $foto = $empleado['foto'];
        $nombre = $empleado['nombre'];
        $funcion = $empleado['funcion'];
        $descripcion = $empleado['descripcion'];
        include 'staff_tarjeta.php';
    }
}
