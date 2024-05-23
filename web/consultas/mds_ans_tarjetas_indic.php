<?php

require_once '../config/db.php';

$conexion = new BaseDatos();
$sql = "SELECT 'Lunes 2/11' Fecha,'0, 2 y 4' Term_DNI,count(*) Pendientes,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and fecha='2020-11-02') ingresaron,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and fecha='2020-11-02' and '2020-11-02'=curdate() and timediff(DATE_FORMAT(NOW(),'%H:%i:%S'),hora)<='01:00:00') ultima_hora
FROM mds_ans_alimentar where mod(dni,10) in (0,2,4) and estado='Pendiente' and municipio='NEUQUEN'
union all
SELECT 'Martes 3/11' Fecha,'1, 3 y 5' Term_DNI,count(*) Total,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and fecha='2020-11-03') ingresaron,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and '2020-11-03'=curdate() and fecha='2020-11-03' and timediff(DATE_FORMAT(NOW(),'%H:%i:%S'),hora)<='01:00:00') ultima_hora
FROM mds_ans_alimentar where mod(dni,10) in (1,3,5) and estado='Pendiente' and municipio='NEUQUEN'
union all
SELECT 'Miércoles 4/11' Fecha,'4, 6 y 8' Term_DNI,count(*) Total,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and fecha='2020-11-04') ingresaron,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and '2020-11-04'=curdate() and fecha='2020-11-04' and timediff(DATE_FORMAT(NOW(),'%H:%i:%S'),hora)<='01:00:00') ultima_hora
FROM mds_ans_alimentar where mod(dni,10) in (4,6,8) and estado='Pendiente' and municipio='NEUQUEN'
union all
SELECT 'Jueves 5/11' Fecha,'7 y 9' Term_DNI,count(*) Total,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and fecha='2020-11-05') ingresaron,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and '2020-11-05'=curdate() and fecha='2020-11-05' and timediff(DATE_FORMAT(NOW(),'%H:%i:%S'),hora)<='01:00:00') ultima_hora
FROM mds_ans_alimentar where mod(dni,10) in (7,9) and estado='Pendiente' and municipio='NEUQUEN'
union all
SELECT 'Viernes 6/11' Fecha,'Remanentes' Term_DNI,count(*) Total,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and fecha='2020-11-06') ingresaron,
(select count(*) from mds_ans_alimentar where estado='Pendiente' and '2020-11-06'=curdate() and fecha='2020-11-06' and timediff(DATE_FORMAT(NOW(),'%H:%i:%S'),hora)<='01:00:00') ultima_hora
FROM mds_ans_alimentar where estado='Pendiente' and '2020-11-06'=curdate() and municipio='NEUQUEN'";

$conexion->Iniciar();
$entregas_ans_tar = array();
if ($conexion->Ejecutar($sql) && $conexion->Cantidad() > 0) {
    while ($fila = $conexion->Registro()) {
        $ans_tar = array(
            "fecha" => $fila['Fecha'],
            "term_dni" => $fila['Term_DNI'],
            "pendientes" => $fila['Pendientes'],
            "ingresaron" => $fila['ingresaron'],
            "ultima_hora"=> $fila['ultima_hora'],
        );
        $entregas_ans_tar[] = $ans_tar;
    }
}
$conexion->Cerrar();
print json_encode($entregas_ans_tar);