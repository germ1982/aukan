<?php

use app\models\Mds_hor_asistencia_reporte;

$query = Mds_hor_asistencia_reporte::find();
/* 'idorganismo' => $idorganismo, 'iddispositivo' => $iddispositivo,
'periodo' => $periodo, 'estado' => $estado, 'eventuales' => $eventuales */
if ($iddispositivo == null) {
    $iddispositivo = -1;
}
if ($idorganismo == null) {
    $idorganismo = -1;
}
if ($periodo == null) {
    $periodo = -1;
}
if ($estado == null) {
    $estado = -1;
}
if ($eventuales == null) {
    $eventuales = -1;
}

$periodo_parts = explode("/", $periodo);
$mes = $periodo_parts[0];
$anio = $periodo_parts[1];
\Yii::$app->db->createCommand("CALL mds_hor_inasistencias(:paramName1, :paramName2)")
    ->bindValue(':paramName1', $anio . '-' . $mes . '-01')
    ->bindValue(':paramName2',  $anio . '-' . $mes . '-' . date("t", strtotime($anio . '-' . $mes . '-01')))
    ->execute();
$query->addSelect([
    "DATE_FORMAT(curdate(),'%d') fecha_dia",
    "case 	WHEN DATE_FORMAT(curdate(),'%m')=1 then 'Enero'
        WHEN DATE_FORMAT(curdate(),'%m')=2 then 'Febrero'
        WHEN DATE_FORMAT(curdate(),'%m')=3 then 'Marzo'
        WHEN DATE_FORMAT(curdate(),'%m')=4 then 'Abril'
        WHEN DATE_FORMAT(curdate(),'%m')=5 then 'Mayo'
        WHEN DATE_FORMAT(curdate(),'%m')=6 then 'Junio'
        WHEN DATE_FORMAT(curdate(),'%m')=7 then 'Julio'
        WHEN DATE_FORMAT(curdate(),'%m')=8 then 'Agosto'
        WHEN DATE_FORMAT(curdate(),'%m')=9 then 'Septiembre'
        WHEN DATE_FORMAT(curdate(),'%m')=10 then 'Octubre'
        WHEN DATE_FORMAT(curdate(),'%m')=11 then 'Noviembre'
        WHEN DATE_FORMAT(curdate(),'%m')=12 then 'Diciembre'
END  fecha_mes", "DATE_FORMAT(curdate(),'%Y') fecha_anio", "temp.idcontacto AS codContacto",
    "group_concat(distinct concat(DAY(fecha),'/',MONTH(fecha)) order by fecha SEPARATOR ' | ' ) AS dia",
    "contpers.legajo",
    "concat(nombre,' ',apellido) empleado",
    "pad.pr PR"
]);
$query->from(["t_inasistencias temp"]);
$query->leftJoin('view_contactos_personas contpers', 'temp.idcontacto=contpers.idcontacto');
$query->leftJoin('mds_org_dispositivo dispositivo', 'contpers.iddispositivo=dispositivo.iddispositivo');
$query->leftJoin('(select padron.legajo,idunidadoperativa,categoria,apellido_nombre,
            sexo,dni,cuil,fecha_nacimiento,
            fecha_ingreso,antiguedad_administrativa,
            antiguedad_privada,antiguedad_total,eventual,pr,titulo 
            from mds_org_padron padron
            join (select mes,anio
            from mds_org_padron
            group by mes,anio
            order by anio desc,mes desc limit 1) temp
            on (temp.mes=padron.mes and temp.anio=padron.anio)) pad', 'contpers.legajo=pad.legajo');
$query->where('DATEDIFF(fecha,curdate())<=0 and contpers.legajo is not null and contpers.legajo!=0 
            and contpers.ficha=1 and not contpers.retenido and contpers.activo=1 and
            ((contpers.fecha_ingreso is not null and contpers.fecha_ingreso<=temp.fecha) 
            or (contpers.fecha_ingreso_planta is not null and contpers.fecha_ingreso_planta<=temp.fecha))
            and ((contpers.eventual=' . $eventuales .' and ' . $eventuales .' =1) 
                or (contpers.planta_politica=1 and '. $eventuales . '=0) or ' . $eventuales . '=-1) 
            and (contpers.iddispositivo=' . $iddispositivo . ' or -1=' . $iddispositivo . ')            
            and (dispositivo.idorganismo =' . $idorganismo . ' or -1=' . $idorganismo . ')');
$query->groupBy('contpers.idcontacto');
//$query->having("estado= '" . $this->estado . "' or (estado='Feriado' and 'Franco'='" . $this->estado . "') or '-1'='" . $this->estado . "'");
$query->orderBy(["empleado" => SORT_ASC]);

$command = $query->createCommand();
$asistencias_datos = $command->queryAll();

?>
<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="img/membrete_nuevo_pri.jpg" width="100%" alt="Subsecretaría de Desarrollo Social">
        <div class="row" style="padding-top: 2%;">
            <div class="col-xs-12" style="text-align: right;">
                <b>Neuquén, <?= $asistencias_datos[0]['fecha_dia'] . ' de ' .
                                $asistencias_datos[0]['fecha_mes'] . ' de ' .
                                $asistencias_datos[0]['fecha_anio'] . ', ' . date('H:i') . 'hs.'; ?>
                </b>
            </div>
        </div>
        <div class="row" style="text-align:left;padding-top: 1%;padding-bottom: 2%;">
            <div class="col-xs-12 text-center">
                <h3><b>Reporte Inasistencias <?= ($eventuales == 1 ? ' - Eventuales' : '') ?></b></h3>
            </div>
        </div>
        <b>Período: <?= $periodo ?></b><br><br>
        <table style="border: 1px solid #999;" class="table table-striped table-bordered detail-view">
            <thead>
                <tr>
                    <th style="border: 1px solid #999; margin: 5px 0; text-align:center; background-color:#D1E3FA;">Legajo</th>
                    <th style="border: 1px solid #999; margin: 5px 0; text-align:center; background-color:#D1E3FA;">Empleado</th>
                    <th style="border: 1px solid #999; margin: 5px 0; text-align:center; background-color:#D1E3FA;">PR</th>
                    <th style="border: 1px solid #999; margin: 5px 0; text-align:center; background-color:#D1E3FA;">Días</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencias_datos as $asistencia) : ?>
                    <tr>
                        <td style="border: 1px solid #999; margin: 5px 0; text-align:left;"><?= $asistencia['legajo']; ?></td>
                        <td style="border: 1px solid #999; margin: 5px 0; text-align:left;"><?= $asistencia['empleado']; ?></td>
                        <td style="border: 1px solid #999; margin: 5px 0; text-align:center;"><?= $asistencia['PR']; ?> </td>
                        <td style="border: 1px solid #999; margin: 5px 0; text-align:center;"><?= $asistencia['dia']; ?> </td>
                    </tr>
                <?php endforeach; ?>
            <tbody>
        </table>
    </div>

</html>