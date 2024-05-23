<?php
$filtro_rubro = '';
$tienefotofrente = true;
$tienefotodorso = true;

/* Consulta SQL:
select ifnull((select descripcion from sds_com_configuracion conf,sds_ent_entrega emisor
where emisor.identrega=ent.emisor and conf.idconfiguracion=emisor.receptor),'Primer Ingreso') emisor,
(select descripcion from sds_com_configuracion conf
where conf.idconfiguracion=ent.receptor) receptor,
ent.fecha_hora,oc,(select descripcion from sds_com_configuracion conf
where conf.idconfiguracion=ent.proveedor) proveedor,cantidad,
(select descripcion from sds_ent_tipo tipo where tipo.idtipo=ent.idtipo) tipo,
(select sum(cantidad) from sds_ent_entrega entreceptor where entreceptor.emisor=ent.identrega and dni is not null) rendidas,
ent.*
from sds_ent_entrega ent
where identrega in (41615,41898,41896,41897,41893,41894);
 */

$query = new yii\db\Query;
//$orderby = "FIELD(identrega, 44873,45045,44947,44946,44936,44935,44932,44927,44926,44924,45045,44947,45502,45500,45501,44946,44936,44935,44932,44927,44926,44924,45502,45500,45501)";
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
	"DATE_FORMAT(curdate(),'%Y') fecha_anio",
	"identrega",
	"ifnull((select descripcion from sds_com_configuracion conf,sds_ent_entrega emisor
	where emisor.identrega=ent.emisor and conf.idconfiguracion=emisor.receptor),'Primer Ingreso') emisor",
	"conf.descripcion receptor", "responsable.dni",
	"ent.fecha_hora", "ifnull(oc,'<i>(Sin Datos)</i>') oc", "ifnull((select descripcion from sds_com_configuracion conf
	where conf.idconfiguracion=ent.proveedor),'<i>(Sin Datos)</i>') proveedor", "cantidad", "(select descripcion 
	from sds_ent_tipo tipo where tipo.idtipo=ent.idtipo) tipo",
	"ifnull((select sum(cantidad) from sds_ent_entrega entreceptor 
	where entreceptor.emisor=ent.identrega),0) rendidas"
])
	->from(["sds_ent_entrega ent"])
	->join("left join", "sds_com_configuracion conf", "conf.idconfiguracion=ent.receptor")
	->join("left join", "sds_ent_responsable responsable", "conf.idconfiguracion=responsable.idresponsable")
	->where("identrega in ($identregas)")
	->orderBy(["FIELD(identrega, $identregas)" => ""]);

$command = $query->createCommand();
$entregas_datos = $command->queryAll();

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;font-size: 10pt;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
		<div class="row" style="padding-top: 2%;">
			<div class="col-xs-offset-6 col-xs-5" style="text-align: right;font-size: 10pt;">Neuquén, <?= $entregas_datos[0]['fecha_dia'] . ' de ' .
																											$entregas_datos[0]['fecha_mes'] . ' de ' . $entregas_datos[0]['fecha_anio'] . ', ' . date('H:i') . 'hs.';  ?>
			</div>
		</div>
		<div class="row" style="text-align:center;padding-top: 1%;padding-bottom: 1%;font-size: 10pt;">
			<div class="col-xs-12">
				<h3><b>Rendición Tribunal de Cuentas</b></h3>
			</div>
		</div>
		<br>
		<?php
		$primer_entrega = true;
		$total_stock = 0;
		$total_entregadas = 0;
		$total_rendidas = 0;
		$indice = 0;
		foreach ($entregas_datos as $entrega_datos) :
			$identrega = $entrega_datos['identrega'];
			$fecha_carga = $entrega_datos['fecha_hora'];
			$unafecha = explode(" ", $fecha_carga);
			$unafecha = explode("-", $unafecha[0]);
			$fecha_carga = $unafecha[2] . "/" . $unafecha[1] . "/" . $unafecha[0];
			$emisor = $entrega_datos['emisor'];
			$receptor = $entrega_datos['receptor'];
			$receptor_dni = $entrega_datos['dni'];
			$oc = $entrega_datos['oc'];
			$proveedor = $entrega_datos['proveedor'];
			$cantidad = $entrega_datos['cantidad'];
			$tipo = $entrega_datos['tipo'];
			$rendidas = $entrega_datos['rendidas'];
			if ($primer_entrega) :
				$primer_entrega = false;
				$total_stock = $total_stock + $cantidad - $rendidas;
				$total_entregadas = $total_entregadas + $rendidas;
		?>
				<div class="row">
					<div class="col-xs-5">
						<b>Entrega: </b><?= $entrega_datos['emisor']; ?>
					</div>
					<div class="col-xs-5" style="text-align: right;">
						<b>Receptor: </b><?= $receptor; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3">
						<b>Fecha: </b><?= $fecha_carga; ?>
					</div>
					<div class="col-xs-7" style="text-align: right;">
						<b>Proveedor: </b><?= $entrega_datos['proveedor']; ?>
					</div>
				</div>
				<div class="row" style="padding-bottom: 3%;">
					<div class="col-xs-7">
						<b>Tipo: </b><?= $entrega_datos['tipo']; ?>
						- Cantidad: <?= $cantidad; ?>
					</div>
					<div class="col-xs-3" style="text-align: right;">
						<b>OC: </b><?= $entrega_datos['oc']; ?>
					</div>
				</div>
				<table class="table table-bordered table-striped" style="font-size: 8pt;">
					<thead>
						<tr>
							<th style="padding: 1px;text-align:center">N°</th>
							<th style="padding: 1px;text-align:center">Fecha</th>
							<th style="padding: 1px;text-align:center">Referente</th>
							<th style="padding: 1px;text-align:center">Beneficiario</th>
							<th style="padding: 1px;text-align:center">DNI</th>
							<th style="padding: 1px;text-align:center">Rendido</th>
							<th style="padding: 1px;text-align:center">Rec./Saldo</th>
						</tr>
					</thead>
					<tbody>
						<?php else : if ($cantidad - $rendidas > 0 || $intermedias) :
						?>
							<tr>
								<td style="padding: 1px;text-align:center"><?= $indice; ?></td>
								<td style="padding: 1px;text-align:center"><?= $fecha_carga; ?></td>
								<td style="padding: 1px;text-align:left"><?= "De " . $emisor . " a <br>" . $receptor; ?></td>
								<td style="padding: 1px;text-align:center"></td>
								<td style="padding: 1px;text-align:left"><?= $receptor_dni; ?></td>
								<td style="padding: 1px;text-align:center"><?= $rendidas . "/" . $cantidad; ?></td>
								<td style="padding: 1px;text-align:right"><?= $cantidad - $rendidas; ?></td>
							</tr>
						<?php endif; ?>
					<?php endif; ?>
					<?php
					$query = new yii\db\Query;
					$query->select([
						"fecha_hora", "cantidad", "dni",
						"(select concat(apellido,', ',nombre) from sds_com_persona p 
							where p.idpersona=sds_ent_entrega.idpersona) nomap"
					])
						->from(["sds_ent_entrega"])
						->where("dni is not null and emisor=" . $identrega)
						->orderBy(["fecha_hora" => SORT_ASC]);
					$command = $query->createCommand();
					$entregas_finales = $command->queryAll();
					foreach ($entregas_finales as $ent_final) :
						$indice++;
						$fecha_final = $ent_final['fecha_hora'];
						$unafecha = explode(" ", $fecha_final);
						$unafecha = explode("-", $unafecha[0]);
						$fecha_final = $unafecha[2] . "/" . $unafecha[1] . "/" . $unafecha[0];
						$cant_final = $ent_final['cantidad'];
						$dni_final = $ent_final['dni'];
						$nom_ap_final = $ent_final['nomap'];
						$total_rendidas = $total_rendidas + $cant_final;
					?>
						<tr>
							<td style="padding: 1px;text-align:center"><?= $indice; ?></td>
							<td style="padding: 1px;text-align:center"><?= $fecha_final; ?></td>
							<td></td>
							<td style="padding: 1px;text-align:left"><?= $nom_ap_final; ?></td>
							<td style="padding: 1px;text-align:left"><?= $dni_final; ?></td>
							<td style="padding: 1px;text-align:center">0/0</td>
							<td style="padding: 1px;text-align:right"><?= $cant_final; ?></td>
						</tr>
				<?php
					endforeach;
					$indice++;
				endforeach; ?>
					</tbody>
				</table>
				<div style="text-align: right;">
					<div class="row">
						<div class="col-xs-offset-7 col-xs-5">
							<h4 style="padding-top: 3%;"><b>Totales:</b></h4>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-offset-7 col-xs-5">
							<b><?= $total_entregadas ?> Entregadas </b>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-offset-7 col-xs-5">
							<b><?= $total_rendidas ?> Rendidas </b>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-offset-7 col-xs-5">
							<b><?= $total_entregadas - $total_rendidas ?> Pendientes de Rendición</b>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-offset-7 col-xs-5">
							<b><?= $total_stock ?> en Stock </b>
						</div>
					</div>
				</div>
	</div>
</body>

</html>