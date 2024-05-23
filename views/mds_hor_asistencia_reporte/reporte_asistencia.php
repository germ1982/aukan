<?php

use app\models\Mds_hor_asistencia_reporte;
use app\models\Mds_hor_registro;
use app\models\Sds_gis_capa_item;
use Da\QrCode\QrCode;

switch(date('m')){
    case 1:
        $mes='Enero';
        break;
    case 2:
        $mes='Febrero';
        break;
    case 3:
        $mes='Marzo';
        break;
    case 4:
        $mes='Abril';
        break;
    case 5:
        $mes='Mayo';
        break;
    case 6:
        $mes='Junio';
        break;
    case 7:
        $mes='Julio';
        break;
    case 8:
        $mes='Agosto';
        break;
    case 9:
        $mes='Septiembre';
        break;
    case 10:
        $mes='Octubre';
        break;
    case 11:
        $mes='Noviembre';
        break;
    case 12:
        $mes='Diciembre';
        break;
}

$query = Mds_hor_asistencia_reporte::find();

$idorganismo = -1;
$eventuales = -1;
if ($desde == null) {
    $desde = -1;
}
if ($hasta == null) {
    $hasta = -1;
}
if ($estado == null) {
    $estado = -1;
}
$iddispositivo = -1;
if ($idcontacto == null) {
    $idcontacto = -1;
}
if ($desde == -1) {
    $desde = date('n/Y', strtotime("-1 month"));
}
if ($hasta == -1) {
    $hasta = date('n/Y', strtotime("-1 month"));
}
$desde_parts = explode("/", $desde);
$mes_desde = $desde_parts[0];
$anio_desde = $desde_parts[1];

$hasta_parts = explode("/", $hasta);
$mes_hasta = $hasta_parts[0];
$anio_hasta = $hasta_parts[1];
$desde = $anio_desde . '-' . $mes_desde . '-01';
$hasta = $anio_hasta . '-' . $mes_hasta . '-' . date("t", strtotime($anio_hasta . '-' . $mes_hasta . '-01'));
$query->addSelect([
    "contacto.idcontacto AS codContacto", "contacto.iddispositivo", "temp.fechPer fecha",
    "CONCAT(DAY(fechPer),'/',MONTH(fechPer)) AS dia",
    "if((contacto.rotativo=0 and (DAYOFWEEK(fechPer) in (1,7)
    or fechPer in (select fecha from mds_hor_feriado))) or
    temp.idfranco is not null,
    if(contacto.rotativo=0 and DAYOFWEEK(fechPer) in (1,7),'Franco',
    if(fechPer in (select fecha from mds_hor_feriado),'Feriado',
    if(francotipo is not null,CONVERT( francotipo USING utf8),'Franco'))),
    if((contacto.idcontacto=codContacto and idregistrohorario is not null) or temp.idcertificacion is not null,'Asistencia',
    if(idlicencia is not null,'Licencia','Inasistencia'))) estado",
    "if(temp.idcertificacion is not null,'ASISTENCIA CERTIFICADA',
    if(idregistrohorario is not null,
    '',
    if(temp.idfranco is not null,francodescr,
    ifnull(if(contacto.rotativo=0,(select descripcion from mds_hor_feriado fer
    where fer.fecha=fechPer),null),
    if(idlicencia is not null,(select licencia.detalle
    from mds_hor_licencia licencia
    where temp.idlicencia=licencia.idlicencia order by desde desc limit 1),''))))) detalle",
    "group_concat(distinct idregistrohorario ORDER BY fecha_reg ASC SEPARATOR ',') detalle_fichada",
    "CONCAT('PR: ',pr,' | Categoría: ',padron.categoria,' | Org./Disp.: ',organismo_desc,' - ',dispositivo_desc) pr_categoria",
    "temp.latitud", "temp.longitud", "foto", "turno_rotativo",
]);
$query->from(["(select  tempPer.fecha fechPer,
CONCAT(DAY(tempPer.fecha),'/',MONTH(tempPer.fecha)) AS dia,regcon.idfranco,franco.descripcion francodescr,ft.descripcion francotipo,regcon.idregistrohorario,idlicencia,
tempPer.idcontacto codContacto,fecha_reg,latitud,longitud,foto,regcon.idcertificacion,dispositivo_desc,organismo_desc
FROM (select periodo.fecha,idcontacto,concat(periodo.fecha,idcontacto) codigo,disp.descripcion dispositivo_desc,
organismo.descripcion organismo_desc
from sds_com_periodo periodo,mds_org_contacto contacto, mds_org_dispositivo disp, mds_org_organismo organismo
where disp.iddispositivo = contacto.iddispositivo 
and organismo.idorganismo=disp.idorganismo and
(contacto.iddispositivo=" . $iddispositivo . "
or contacto.idcontacto=" . $idcontacto . "
or (-1=" . $iddispositivo . " and 0<" . $idorganismo . "))
and (disp.idorganismo =" . $idorganismo . "
or -1=" . $idorganismo . ")            
and periodo.fecha BETWEEN '$desde' and '$hasta 23:59:00') tempPer            
left join (SELECT periodo.periodo AS periodo, periodo.fecha AS fecha, NULL AS idfranco, NULL AS idcertificacion, 
reg.idregistrohorario AS idregistrohorario, NULL AS idlicencia, reg.idcontacto AS codContacto, reg.fecha AS fecha_reg
FROM sds_com_periodo periodo
JOIN mds_hor_registro reg ON DATE_FORMAT(reg.fecha, '%Y-%m-%d') = periodo.fecha
WHERE reg.idcontacto IS NOT NULL
and reg.idcontacto=" . $idcontacto . " 
and reg.fecha BETWEEN '$desde' and '$hasta 23:59:00'
UNION 
SELECT periodo.periodo AS periodo, periodo.fecha AS fecha, franco.idfranco AS idfranco, NULL AS idcertificacion,
NULL AS idregistrohorario, NULL AS idlicencia, franco.idcontacto AS codContacto, NULL AS fecha_reg
FROM sds_com_periodo periodo
LEFT JOIN mds_hor_franco franco ON franco.fecha = periodo.fecha
WHERE franco.idcontacto IS NOT NULL
and franco.idcontacto=" . $idcontacto . " 
and franco.fecha BETWEEN '$desde' and '$hasta 23:59:00'
UNION 
SELECT periodo.periodo AS periodo, periodo.fecha AS fecha, NULL AS idfranco, certificacion.idcertificacion AS idcertificacion,
NULL AS idregistrohorario, NULL AS idlicencia, certificacion.certificado AS codContacto, NULL AS fecha_reg
FROM sds_com_periodo periodo
LEFT JOIN mds_hor_certificacion certificacion ON certificacion.periodo_mes = MONTH(periodo.fecha)
AND certificacion.periodo_anio = YEAR(periodo.fecha)
and certificacion.certificado=" . $idcontacto . " 
and periodo.fecha BETWEEN '$desde' and '$hasta 23:59:00'
UNION 
SELECT periodo.periodo AS periodo, periodo.fecha AS fecha, NULL AS idfranco, NULL AS idcertificacion, NULL AS idregistrohorario,
licencia.idlicencia AS idlicencia, licencia.idcontacto AS codContacto, NULL AS fecha_reg
FROM sds_com_periodo periodo
LEFT JOIN mds_hor_licencia licencia ON licencia.desde <= periodo.fecha AND licencia.hasta >= periodo.fecha
WHERE (0 <> licencia.idcontacto)
and licencia.idcontacto=" . $idcontacto . " 
and periodo.fecha BETWEEN '$desde' and '$hasta 23:59:00'
ORDER BY periodo , codContacto , fecha , fecha_reg) regcon ON concat(regcon.fecha,regcon.codContacto)=tempPer.codigo
left join mds_hor_registro reg ON reg.idregistrohorario=regcon.idregistrohorario
left join mds_hor_franco franco on regcon.idfranco = franco.idfranco
left join sds_com_configuracion ft on ft.idconfiguracion = franco.tipo) temp "]);
$query->join('join', 'mds_org_contacto contacto', 'temp.codContacto=contacto.idcontacto');
$query->join('join', 'sds_com_persona pers', 'pers.idpersona=contacto.idpersona');
$query->join('left join', 'mds_org_padron padron', 'pers.documento=padron.dni');
$query->where('((contacto.eventual=' . $eventuales .
    ' and ' . $eventuales . '=1) or (contacto.planta_politica=1 and '
    . $eventuales . '=0) or ' . $eventuales . '=-1)');
$query->groupBy('fecha, contacto.idcontacto');
$query->having("estado= '" . $estado . "' or 
(estado='Feriado' and 'Franco'='" . $estado . "') 
or '-1'='" . $estado . "'");
$query->orderBy(["fecha" => SORT_ASC, "codContacto" => SORT_ASC]);

$command = $query->createCommand();
$asistencias_datos = $command->queryAll();

$query_contacto = Mds_hor_asistencia_reporte::find();
$query_contacto->addSelect(['c.*', 'concat(p.apellido,\', \',p.nombre) nombre', 'p.documento'])
    ->from('mds_org_contacto c')
    ->innerJoin('sds_com_persona p', 'c.idpersona=p.idpersona')
    ->andFilterWhere([
        'c.idcontacto' => $idcontacto,
    ]);
$command = $query_contacto->createCommand();
$datos_contacto = $command->queryOne();

$capa_item_empleado = Sds_gis_capa_item::findBySql(
    "SELECT ci.* FROM sds_gis_capa_item ci JOIN mds_org_dispositivo d ON d.idcapaitem=ci.idcapaitem"
)
    ->one();
?>
<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="img/membrete_nuevo_pri.jpg" width="100%" alt="Subsecretaría de Desarrollo Social">
        <div class="row" style="padding-top: 2%;">
            <div class="col-xs-offset-5 col-xs-6" style="text-align: right;">
                <b>Neuquén, <?= date('d')?> de <?=$mes?> de <?=date('Y H:i')?>hs.</b>
            </div>
        </div>
        <div class="row" style="text-align:center;padding-top: 3%;padding-bottom: 1%;">
            <div class="col-xs-12">
                <b><?= strtoupper($datos_contacto['nombre']); ?> -
                    Legajo: <?= $datos_contacto['legajo'] ?> -
                    DNI <?= $datos_contacto['documento'] ?> -
                    <?= ($asistencias_datos[0] ? $asistencias_datos[0]['pr_categoria'] : "") ?></b><br>
                <b>-<?= $capa_item_empleado->descripcion ?>-</b>
            </div>
        </div>
        <div class="row" style="text-align:left;padding: 1% 0;">
            <div class="col-xs-12">
                Detalle de asistencias correspondientes al período comprendido entre el
                <b><?= date('01/m/Y', strtotime(str_replace('/', '-', $desde))) ?></b> y el
                <b><?= date('t/m/Y', strtotime(str_replace('/', '-', $hasta))) ?></b>
            </div>
        </div>
        <table class="table table-striped table-bordered detail-view">
            <thead>
                <tr>
                    <th style="border: 1px solid #999; text-align: center; padding:4px; background-color:#D1E3FA;">Fecha</th>
                    <th style="border: 1px solid #999; text-align: center; padding:4px; background-color:#D1E3FA;">Día</th>
                    <th style="border: 1px solid #999; text-align: center; padding:4px; background-color:#D1E3FA;">Estado</th>
                    <th style="border: 1px solid #999; text-align: center; padding:4px; background-color:#D1E3FA;">Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencias_datos as $asistencia) : ?>
                    <tr>
                        <td style="border: 1px solid #999; text-align:center; padding:4px;"><?= date('d/m', strtotime($asistencia['fecha']))?></td>
                        <td style="border: 1px solid #999; text-align:center; padding:4px;">
                            <?php
                            switch(date('N', strtotime($asistencia['fecha']))){
                                case 1: 
                                    $dia = 'Lun';
                                    break;
                                case 2: 
                                    $dia = 'Mar';
                                    break;
                                case 3: 
                                    $dia = 'Mié';
                                    break;
                                case 4: 
                                    $dia = 'Jue';
                                    break;
                                case 5: 
                                    $dia = 'Vie';
                                    break;
                                case 6: 
                                    $dia = 'Sáb';
                                    break;
                                case 7: 
                                    $dia = 'Dom';
                                    break;
                            }
                            echo $dia;
                            $asistencia['fecha']; ?>
                        </td>
                        <td style="border: 1px solid #999; text-align:center; padding:4px;">
                            <?php
                            $color='default';
                            if($asistencia['estado']=='Inasistencia'){
                                $color = "danger";
                            }
                            if($asistencia['estado']=='Asistencia'){
                                $color = "success";
                            }
                            ?>
                            <?= "<span class='text-$color'>".$asistencia['estado'].'</span>';?>
                        </td>
                        <td style="border: 1px solid #999; text-align:center; padding:4px;">
                            <?php
                            echo ($asistencia['detalle']!=''?$asistencia['detalle'].'<br>':'');
                            $resultado = "";
                            $nocturno_detalle = ""; /* Para concatenar los horarios nocturnos*/
                            $nocturno_count = 0; /* Para contar los horarios nocturnos*/
                            if (!empty($asistencia['detalle_fichada'])) {
                                $fichadas = explode(',', $asistencia['detalle_fichada']);
                                foreach ($fichadas as $idregistro) {
                                    $registro = Mds_hor_registro::findOne($idregistro);
                                    if ($registro != null) {
                                        $origen = "";
                                        switch ($registro->origen) {
                                            case (Mds_hor_registro::ORIGEN_CICLO):
                                                $origen = "Ciclo";
                                                break;
                                            case (Mds_hor_registro::ORIGEN_GUARDIA):
                                                $origen = "Guardia";
                                                break;
                                            case (Mds_hor_registro::ORIGEN_IMPORTACION):
                                                $origen = "Importación";
                                                break;
                                            case (Mds_hor_registro::ORIGEN_MANUAL):
                                                $origen = "Manual";
                                                break;
                                        }
                                        $resultado = $resultado . ($resultado != "" ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "") . date_format(date_create($registro->fecha), "H:i") . " ($origen)";
                                        if($registro->horario_nocturno && $nocturno_count==0){
                                            $nocturno_count++;
                                            $nocturno_detalle=date_format(date_create($registro->fecha), "H:i");
                                        }elseif($registro->horario_nocturno && $nocturno_count==1){
                                            $resultado = date_format(date_create($registro->fecha), "H:i")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $nocturno_detalle  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ($origen)";
                                            $nocturno_count--;
                                        }
                                    }
                                }
                                if ($resultado == "") {
                                    $resultado = $asistencia['detalle'];
                                }
                                $resultado = ($asistencia['turno_rotativo'] ? "TURNO ROTATIVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "") . $resultado;
                            }
                            echo $resultado;
                            ?> </td>
                    </tr>
                <?php endforeach; ?>
            <tbody>
        </table>
    </div>
    <footer style="position: absolute; bottom:0; margin-top:20px;">
        <div class="row">
        <img src="img/footer_ds.png" class="col-xs-9" alt="Subsecretaría de Desarrollo Social" style="opacity: 0.87; margin-top:20px;">
            <?php $url = Yii::$app->request->hostInfo . Yii::$app->request->getUrl();
                $qr = (new QrCode($url))->setSize(120);?>
            <img class="col-xs-3" src="<?= $qr->writeDataUri() ?>" style="width:100px; height:100px;">
        </div>
    </footer>
</body>

</html>