<?php

$query = new yii\db\Query;
$query->select([
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
END  fecha_mes",
	"DATE_FORMAT(curdate(),'%Y') fecha_anio", "cantidad", "(select descripcion
from sds_ent_tipo tipo
where tipo.idtipo=sds_ent_entrega.idtipo) tipo", "ifnull((select descripcion
from sds_com_configuracion conf
where conf.idconfiguracion=(select receptor from sds_ent_entrega entemisor where entemisor.identrega=sds_ent_entrega.emisor)),'Primer Ingreso') emisor", "(select descripcion
from sds_com_configuracion conf
where conf.idconfiguracion=sds_ent_entrega.receptor) receptor", "identrega", "observaciones", "fecha_hora",
"ifnull((select rendiciones_pendientes from sds_ent_solicitud_intermedia solic where solic.idsolicitudintermedia=sds_ent_entrega.idsolicitudintermedia),'') pendientes",
""
])
	->from(["sds_ent_entrega"])
	->where("identrega in ($ids)");

$command = $query->createCommand();
$entrega_datos = $command->queryAll();

$fecha_carga = $entrega_datos[0]['fecha_hora'];
$unafecha = explode(" ", $fecha_carga);
$unafecha = explode("-", $unafecha[0]);
$fecha_carga = $unafecha[2] . "/" . $unafecha[1] . "/" . $unafecha[0];

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
		<div class="row" style="padding-top: 2%;">
			<div class="col-xs-offset-6 col-xs-5" style="text-align: right;">Neuquén, <?= $entrega_datos[0]['fecha_dia'] . ' de ' . $entrega_datos[0]['fecha_mes'] . ' de ' . $entrega_datos[0]['fecha_anio'] . ', ' . date('H:i') . 'hs.';  ?>
			</div>
		</div>
		<div class="row" style="text-align:center;padding-top: 3%;padding-bottom: 2%;font-size: 12pt;">
			<div class="col-xs-12">
				<b>Acta de Entrega</b><br>
				Ministerio de Desarrollo Social y Trabajo
			</div>
		</div>
		<div class="row" style="padding-top: 2%;padding-bottom: 1%;font-size: 11pt;">
			<div class="col-xs-1">
				<b>Emisor </b>
			</div>
			<div class="col-xs-5">
				<?= $entrega_datos[0]['emisor']; ?>
			</div>
		</div>
		<div class="row" style="padding-bottom: 2%;font-size: 11pt;">
			<div class="col-xs-1">
				<b>Fecha</b>
			</div>
			<div class="col-xs-2">
				<?= $fecha_carga ?>
			</div>
			<div class="col-xs-2">
				<b>Receptor </b>
			</div>
			<div class="col-xs-3">
				<?= $entrega_datos[0]['receptor']; ?>
			</div>
		</div>
		<div style="padding-top: 5%;font-size: 11pt;">
			<!-- Acá arrancan los datos individuales de cada entrega-->
			<?php
			$pendientes = "";
			foreach ($entrega_datos as $entrega) :
				if ($entrega['pendientes'] != "") {
					$pendientes = $entrega['pendientes'];
				}
			?>
				<div class="row">
					<div class="col-xs-2"><b>N°</b> <?= $entrega['identrega']; ?> </div>
					<div class="col-xs-1"><b>Tipo </b></div>
					<div class="col-xs-3"> <?= $entrega['tipo']; ?> </div>
					<div class="col-xs-2"><b>Cantidad </b> <?= $entrega['cantidad']; ?> </div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ($pendientes != "") : ?>
			<div class="row" style="padding-top: 5%;font-size: 11pt;">
				<div class="col-xs-5"><b>Pendientes de Rendición: </b></div>
			</div>
			<div class="row">
				<div class="col-xs-8"> <?= $pendientes; ?> </div>
			</div>
		<?php endif; ?>
</body><br>
<footer style="padding-top: 10%;">
	<div class="row">
		<div class="col-xs-offset-7 col-xs-5" style="text-align: center;">
			<div>
				<br><br><br>---------------------------------------------
			</div>
			<div>
				Firma
			</div>
		</div>
	</div>
</footer>

</html>