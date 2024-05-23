<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_bdc_equipo;
use app\models\Sds_cel_linea;
use app\models\Sds_cel_movimiento;
use app\models\Sds_cel_movimiento_linea;
use app\models\Sds_cel_plan;
use app\models\Sds_com_configuracion;

$query = Sds_cel_linea::find();
/*
$sql_desde = '';
$sql_hasta = '';
if ($fdesde != null) {
    $fecha_desde_aux = date('Y-m-d', strtotime(str_replace('/', '-', $fdesde)));
    $sql_desde = "fecha_entrega >= '$fecha_desde_aux'";
}
if ($fhasta != null) {
    $fecha_hasta_aux = date('Y-m-d', strtotime(str_replace('/', '-', $fhasta)));
    $sql_hasta = "fecha_entrega <= '$fecha_hasta_aux'";
}
*
$having = '';
if (!($iddispositivo == null)) {
    $having = 'iddispositivo=' . $iddispositivo;
}
/* if (!($ultimo_importe == null)) {
if ($having == '') {
$having = 'ultimo_importe = ' . $ultimo_importe;
} else {
$having = ' and ultimo_importe = ' . $ultimo_importe;
}
} */
//$mysql_ultimo_importe = '(SELECT SUM(i.cantidad) as ultimo_importe from sds_cel_factura f inner join sds_cel_factura_item i on f.idfactura = i.idfactura Where f.fecha_carga = (Select max(fecha_carga) from sds_cel_factura) and i.linea = sds_cel_linea.numero)';
/*
$query->addSelect([
    "DATE_FORMAT(curdate(),'%d') fecha_dia",
    "case
        WHEN DATE_FORMAT(curdate(),'%m')=1 then 'Enero'
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
        END  fecha_mes",
        "DATE_FORMAT(curdate(),'%Y') fecha_anio",
        'sds_cel_linea.*',
        "(SELECT iddispositivo FROM mds_org_contacto c
        where c.idcontacto=sds_cel_linea.idcontacto) AS iddispositivo",
        "(select concat(nombre,' ',apellido) from mds_org_contacto c
            join sds_com_persona p on p.idpersona=c.idpersona
            where idcontacto=sds_cel_linea.idcontacto) responsable",
        "(select descripcion from mds_org_organismo where idorganismo=sds_cel_linea.organismo_padre) organismo_cuenta",
        "(select descripcion from mds_org_organismo where idorganismo=sds_cel_linea.idorganismo) organismo",
        "(select concat(disp.descripcion,'<br>', org.descripcion)
        from mds_org_dispositivo disp
        join mds_org_organismo org on org.idorganismo=disp.idorganismo
        where (SELECT iddispositivo FROM mds_org_contacto c
        where c.idcontacto=sds_cel_linea.idcontacto)=disp.iddispositivo) dispositivo",
        "(select descripcion from sds_com_configuracion conf where conf.idconfiguracion=sds_cel_linea.equipo_marca) marca",
]);
*/
$query->select('l.*')
->from('sds_cel_linea l');

if($idcontacto!=null || $idorganismo!=null || $iddispositivo!=null){
    $query->innerJoin('sds_bdc_equipo e', 'l.idequipo=e.idequipo');
}

if($iddispositivo!=null){
    $query->innerJoin('mds_org_contacto c', 'e.responsable=c.idcontacto');
}

$query->andFilterWhere([
    'organismo_padre' => $organismo_padre,
    'idplan' => $idplan,
    'estado' => $estado,
    'e.responsable' => $idcontacto,
    'e.idorganismo' => $idorganismo
]);

$query->andFilterWhere(['like', 'numero', $numero])
    ->andFilterWhere(['like', 'observaciones', $observaciones]);
    //->andWhere($sql_desde)->andWhere($sql_hasta);
$query->orderBy(['l.fecha_entrega'=>SORT_DESC]);
$command = $query->createCommand();
$datos_corpo = $command->queryAll();
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
    case 6;
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
?>
<html>
<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
	<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
	<div class="row" style="padding-top: 2%;">
			<div class="col-xs-offset-6 col-xs-5" style="text-align: right;">
            Neuquén, <?=date('d').' de '.$mes.' de '.date('Y').' '.date('H:i').' hs.';?>
			</div>
		</div>
		<div class="row" style="text-align:center;padding-top: 2%;padding-bottom: 2%;font-size: 12pt;">
			<div class="col-xs-12">
				<b>Listado de Celulares Corporativos</b><br>
			</div>
		</div>
		<div class="row" style="padding-top: 2%;padding-bottom: 2%;font-size: 9pt;">
			<?=$numero != null ? "<div class=\"col-xs-3\"><b>Número: </b>" . $numero . "</div>" : "";?>
			<?=$idplan != null ? "<div class=\"col-xs-3\"><b>Plan: </b>" . Sds_cel_plan::findOne($idplan)->descripcion . "</div>" : "";?>

            <?php
                if($organismo_padre!=null){
                    $organismo_cuenta=Mds_org_organismo::findOne($organismo_padre);
                    if($organismo_cuenta!=null){
                        echo "<div class=\"col-xs-6\"><b>Organismo Cuenta: </b>" .$organismo_cuenta->descripcion. "</div>";
                    }
                }
            ?>
			
            <?=$idcontacto != null ? "<div class=\"col-xs-4\"><b>Responsable: </b>" . $datos_corpo[0]['responsable'] . "</div>" : "";?>
			<?=$idorganismo != null ? "<div class=\"col-xs-6\"><b>Organismo: </b>" . Mds_org_organismo::findOne($idorganismo)->descripcion. "</div>" : "";?>
			
			<?=$idusuario != null ? "<div class=\"col-xs-3\"><b>Usuario: </b>" . Mds_seg_usuario::findOne($idusuario)->user . "</div>" : "";?>
			<?=$observaciones != null ? "<div class=\"col-xs-4\"><b>Observaciones: </b>" . $observaciones . "</div>" : "";?>

            <?php if($iddispositivo!=null):
                $dispositivo=Mds_org_dispositivo::findOne($iddispositivo);
                $organismo=Mds_org_organismo::findOne($dispositivo->idorganismo);?>
                <div class="col-xs-12">
                    <b>Dispositivo: </b><?=$dispositivo->descripcion;?> (<?=$organismo->descripcion;?>)
                </div>
            <?php endif; ?>
		</div>
		<table style="border: 1px solid #ccc;" class="table table-striped table-bordered detail-view">
			<thead>
				<tr>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Línea</th>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Org.Cuenta</th>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Responsable</th>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Organismo</th>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Dispositivo</th>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Marca</th>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Modelo</th>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Observ.</th>
					<th style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">Estado</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($datos_corpo as $corpo): ?>
					<tr>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;"><?=$corpo['numero'];?> </td>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">
                            <?php
                            if(isset($organismo_cuenta)){
                                echo $organismo_cuenta->descripcion;
                            }else{
                                $organismo_cuenta=Mds_org_organismo::findOne($corpo['organismo_padre']);
                                if($organismo_cuenta!=null){
                                    echo $organismo_cuenta->descripcion;
                                    unset($organismo_cuenta);
                                }else{
                                    echo '-';
                                }
                            }
                            ?>
                        </td>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">
                            <?php
                                $equipo=Sds_bdc_equipo::findOne($corpo['idequipo']);
                                if($equipo!=null){
                                    $responsable=Mds_org_contacto::findBySql(
                                        "SELECT c.*, p.nombre nombre, p.apellido apellido 
                                        FROM mds_org_contacto c
                                        JOIN sds_com_persona p ON c.idpersona=p.idpersona
                                        WHERE c.idcontacto=$equipo->responsable")->one();
                                        if($responsable!=null){
                                            echo $responsable->apellido.', '.$responsable->nombre;
                                        }
                                }else{
                                    $responsable=null;
                                    echo '-';
                                }
                            ?>
                        </td>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">
                            <?php
                                if($equipo!=null){
                                    $organismo=Mds_org_organismo::getDescripcion($equipo->idorganismo);
                                    echo $organismo;
                                }else{
                                    echo '-';
                                }
                            ?>
                        </td>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">
                            <?php 
                                if($responsable!=null){
                                    $dispositivo=Mds_org_dispositivo::findOne($responsable->iddispositivo);
                                    if(isset($dispositivo)){
                                        echo $dispositivo->descripcion;
                                    }
                                }else{
                                    echo '-';
                                }
                            ?>
                        </td>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">
                            <?php
                                if($equipo!=null){
                                    $marca=Sds_com_configuracion::getDescripcion($equipo->marca);
                                    echo $marca;
                                }else{
                                    echo '-';
                                }
                            ?>
                        </td>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">
                            <?php
                                if(isset($equipo)){
                                    echo $equipo->modelo;
                                }else{
                                    echo '-';
                                }
                            ?>
                        </td>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">
                            <?=$corpo['observaciones'];?>
                        </td>
						<td style="border: 1px solid #ccc; margin: 5px 0; text-align: center;">
                            <?php 
                            $ultimo_movimiento=Sds_cel_movimiento_linea::findBySql(
                                "SELECT *  FROM sds_cel_movimiento_linea 
                                WHERE idmovimientolinea IN(SELECT max(idmovimientolinea) FROM sds_cel_movimiento_linea WHERE idlinea=".$corpo['idlinea'].")"
                            )->one();
                            if($ultimo_movimiento!=null){
                                $estado=Sds_com_configuracion::getDescripcion($ultimo_movimiento->tipo);
                                echo $estado;
                            }else{
                                echo '-';
                            }
                            ?>
                        </td>
					</tr>

				<?php endforeach;?>
			<tbody>
		</table>
	</div>

</html>