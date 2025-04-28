<?php
require_once '../config/db.php'; // Ajusta la ruta según sea necesario
include 'evento_modal.php';

$db = new BaseDatos();
if ($db->Iniciar()) {
      $consulta = "SELECT e.idevento, e.descripcion, e.activo, e.fotos, e.titulo, tec.descripcion as tipo_evento,
                  DATE_FORMAT(e.fecha, '%d/%m/%Y') AS fecha,
                  o.descripcion as organismo,
                  d.descripcion as dispositivo
                  FROM informatica_web_eventos e 
                  JOIN organismo_dispositivo d on e.iddispositivo = d.iddispositivo 
                  JOIN organismo o on o.idorganismo = d.idorganismo
                  join configuracion tec on tec.id_configuracion = e.tipo_evento
                  WHERE e.activo = 1
                  ORDER BY e.fecha DESC;";
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

      echo '<div class="row" style="padding-left:10%;padding-right:10%">';

      $contador = 0;
      foreach ($eventos as $e) {
            $contador++;
            $fecha = $e['fecha'];
            $titulo = $e['titulo'];
            $organismo = $e['organismo'];
            $dispositivo = $e['dispositivo'];
            $descripcion = $e['descripcion'];
            $fotos = $e['fotos'];
            $tipo_evento = $e['tipo_evento'];

            include 'evento_tarjeta_independiente.php';
      }

      echo '</div>'; // cierre de row

}


?>

