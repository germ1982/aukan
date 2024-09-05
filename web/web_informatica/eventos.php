<?php
require_once '../config/db.php'; // Ajusta la ruta según sea necesario

$db = new BaseDatos();
if ($db->Iniciar()) {
      $consulta = "SELECT e.idevento, e.descripcion, e.activo, e.fotos, e.titulo,
                  DATE_FORMAT(e.fecha, '%d/%m/%Y') AS fecha,
                  o.descripcion as organismo,
                  d.descripcion as dispositivo
                  FROM informatica_web_eventos e 
                  JOIN organismo_dispositivo d on e.iddispositivo = d.iddispositivo 
                  JOIN organismo o on o.idorganismo = d.idorganismo
                  WHERE e.activo = 1
                  ORDER BY fecha DESC;";
      $result = $db->Select($consulta);
      if ($result) {
            mostrar_eventos($result);
      }
      $db->Cerrar();
} else {
      echo "Error al conectar a la base de datos";
}


function mostrar_eventos($eventos)
{
      /* echo '<div class="row titulo_seccion" style="width: 90%; margin: 0 auto;" ;>
    <u>Staff</u>
    </div>
    <br>'; */

      foreach ($eventos as $e) {
            $fecha = $e['fecha'];
            $titulo = $e['titulo'];
            $organismo = $e['organismo'];
            $dispositivo = $e['dispositivo'];
            $descripcion = $e['descripcion'];
            $fotos = $e['fotos'];

      
            include 'evento_tarjeta.php';
      }
}
