<?php
$filtro_rubro = '';
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
    "case 	WHEN DATE_FORMAT(fecha_hora,'%m')=1 then 'Enero'
		WHEN DATE_FORMAT(fecha_hora,'%m')=2 then 'Febrero'
		WHEN DATE_FORMAT(fecha_hora,'%m')=3 then 'Marzo'
		WHEN DATE_FORMAT(fecha_hora,'%m')=4 then 'Abril'
		WHEN DATE_FORMAT(fecha_hora,'%m')=5 then 'Mayo'
		WHEN DATE_FORMAT(fecha_hora,'%m')=6 then 'Junio'
		WHEN DATE_FORMAT(fecha_hora,'%m')=7 then 'Julio'
		WHEN DATE_FORMAT(fecha_hora,'%m')=8 then 'Agosto'
		WHEN DATE_FORMAT(fecha_hora,'%m')=9 then 'Septiembre'
		WHEN DATE_FORMAT(fecha_hora,'%m')=10 then 'Octubre'
		WHEN DATE_FORMAT(fecha_hora,'%m')=11 then 'Noviembre'
		WHEN DATE_FORMAT(fecha_hora,'%m')=12 then 'Diciembre'
END  mes_entrega",
    "DATE_FORMAT(curdate(),'%Y') fecha_anio", "DATE_FORMAT(fecha_hora,'%Y') anio_entrega", "cantidad", "(select descripcion
from sds_ent_tipo tipo
where tipo.idtipo=sds_ent_entrega.idtipo) tipo", "ifnull((select descripcion
from sds_com_configuracion conf
where conf.idconfiguracion=(select receptor from sds_ent_entrega entemisor where entemisor.identrega=sds_ent_entrega.emisor)),'Primer Ingreso') emisor",
    "conf.descripcion receptor", "responsable.dni", "responsable.mail", "responsable.telefono", "responsable.dni_frente", "responsable.dni_dorso", "observaciones", "fecha_hora",
    "ifnull((select rendiciones_pendientes from sds_ent_solicitud_intermedia solic 
	where solic.idsolicitudintermedia=sds_ent_entrega.idsolicitudintermedia),'') pendientes",
    "(select concat(apellido,', ',nombre) from sds_com_persona p where p.idpersona=sds_ent_entrega.persona_retira) retira,
(select concat(p.apellido,', ',p.nombre)
from sds_com_persona p,mds_org_contacto c,mds_seg_usuario u
where p.idpersona=c.idpersona and u.idcontacto=c.idcontacto and u.idusuario=sds_ent_entrega.usuario_entrega) entrega ",
    "numero_desde", "numero_hasta", "(select sum(cantidad) 
	from sds_ent_cierre
	where sds_ent_cierre.identrega=sds_ent_entrega.identrega) saldo"
])
    ->from(["sds_ent_entrega"])
    ->join("left join", "sds_com_configuracion conf", "conf.idconfiguracion=sds_ent_entrega.receptor")
    ->join("left join", "sds_ent_responsable responsable", "conf.idconfiguracion=responsable.idresponsable")
    ->where(["identrega" => $identrega]);

$command = $query->createCommand();
$entrega_datos = $command->queryOne();

$fecha_carga = $entrega_datos['fecha_hora'];
$unafecha = explode(" ", $fecha_carga);
$unafecha = explode("-", $unafecha[0]);
$fecha_carga = $unafecha[2] . "/" . $unafecha[1] . "/" . $unafecha[0];


$query = new yii\db\Query;
$query->select(['conf.descripcion', 'sum(cantidad) cantidad', 'CONCAT(\'N&deg; \',group_concat(numero SEPARATOR \' - \')) observaciones'])
    ->from(["sds_ent_cierre cierre"])
    ->join("join", "sds_com_configuracion conf", "conf.idconfiguracion=cierre.motivo")
    ->where(["identrega" => $identrega])
    ->groupBy("conf.idconfiguracion");

$command = $query->createCommand();
$cierre_datos = $command->queryAll();

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
		<div class="row" style="padding-top: 2%;">
			<div class="col-xs-offset-6 col-xs-5" style="text-align: right;"><b>Neuquén, <?= $entrega_datos['fecha_dia'] . ' de ' .
                                                                                                $entrega_datos['fecha_mes'] . ' de ' . $entrega_datos['fecha_anio'] . ', ' . date('H:i') . 'hs.';  ?>
				</b></div>
		</div>
		<div class="row" style="text-align:center;padding-top: 2%;padding-bottom: 2%;">
			<div class="col-xs-12">
				Carga de Rendiciones de <?= $entrega_datos['tipo']; ?> - Entrega Nº <?= $identrega; ?><br>
			</div>
		</div>
		<div class="row" style="text-align:left;padding-top: 2%;padding-bottom: 2%;">
			<div class="col-xs-12">
				Se procede a informar el resultado de la carga de rendiciones para la entrega del <b>mes de <?= $entrega_datos['mes_entrega']; ?>
					de <?= $entrega_datos['anio_entrega']; ?></b> que corresponde a <?= $entrega_datos['receptor']; ?>, referente de Neuquén Capital.
				<br>
				Sobre un total de <?= $entrega_datos['cantidad']; ?> bonos entregados, se cargaron satisfactoriamente <b><?= $entrega_datos['cantidad'] - $entrega_datos['saldo']; ?></b>.
				<?php if ($estado_cierre!=2): ?>
					Los <b><?= $entrega_datos['saldo']; ?></b> restantes tienen los siguientes errores:
				<?php endif; ?>
			</div>
		</div>
		<?php if ($estado_cierre!=2): ?>
		<table style="border: 1px solid #ccc;" class="table table-striped table-bordered detail-view">
			<thead>
				<tr>
					<th style="border: 1px solid #ccc;margin: 5px 0;">Error</th>
					<th style="border: 1px solid #ccc;margin: 5px 0;">Cantidad</th>
					<th style="border: 1px solid #ccc;margin: 5px 0;">Observaciones</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cierre_datos as $cierre) : ?>
					<tr>
						<td style="border: 1px solid #ccc;margin: 5px 0;"><?= $cierre['descripcion']; ?> </td>
						<td style="border: 1px solid #ccc;margin: 5px 0;"><?= $cierre['cantidad']; ?> </td>
						<td style="border: 1px solid #ccc;margin: 5px 0;"><?= $cierre['observaciones']; ?> </td>
					</tr>
				<?php endforeach; ?>
			<tbody>
		</table>
		<?php endif; ?>
	</div>
	<div class="row" style="text-align:left;padding-top: 2%;padding-bottom: 2%;">
		<div class="col-xs-12">
			Se adjunta planilla de rendición.
		</div>
	</div>
	<div class="row" style="text-align:center;padding-top: 4%;padding-bottom: 2%;">
		<div class="col-xs-12">
			Saludo muy atentamente.
		</div>
	</div>
</body><br>
<footer style=" left: 0;
  padding-top: 2%;
  width: 100%;">
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