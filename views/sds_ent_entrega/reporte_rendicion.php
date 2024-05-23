<?php

use app\models\Sds_ent_entrega;

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
$query->select([
	"identrega",
	"ifnull((select descripcion from sds_com_configuracion conf,sds_ent_entrega emisor
	where emisor.identrega=ent.emisor and conf.idconfiguracion=emisor.receptor),'Primer Ingreso') emisor",
	"(select descripcion from sds_com_configuracion conf
	where conf.idconfiguracion=ent.receptor) receptor",
	"ent.fecha_hora", "ifnull(oc,'<i>(Sin Datos)</i>') oc", "ifnull((select descripcion from sds_com_configuracion conf
	where conf.idconfiguracion=ent.proveedor),'<i>(Sin Datos)</i>') proveedor", "cantidad", "(select descripcion 
	from sds_ent_tipo tipo where tipo.idtipo=ent.idtipo) tipo",
	"ifnull((select sum(cantidad) from sds_ent_entrega entreceptor 
	where entreceptor.emisor=ent.identrega),0) entregadas",
	"numero_desde", "numero_hasta"
])
	->from(["sds_ent_entrega ent"])
	->where("identrega in ($identregas)")
	->orderBy(["FIELD(identrega, $identregas)" => ""]);

$command = $query->createCommand();
$entregas_datos = $command->queryAll();

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<h3><b><?= $detalle != 'false' ? "Detalle" : "Resumen" ?></b></h3>
		<br>
		<?php
		$primer_entrega = true;
		$total_stock = 0;
		$total_entregadas = 0;
		$total_rendidas = 0;
		foreach ($entregas_datos as $entrega_datos) :
			$identrega = $entrega_datos['identrega'];
			$fecha_carga = $entrega_datos['fecha_hora'];
			$unafecha = explode(" ", $fecha_carga);
			$unafecha = explode("-", $unafecha[0]);
			$fecha_carga = $unafecha[2] . "/" . $unafecha[1] . "/" . $unafecha[0];
			$emisor = $entrega_datos['emisor'];
			$receptor = $entrega_datos['receptor'];
			$oc = $entrega_datos['oc'];
			$proveedor = $entrega_datos['proveedor'];
			$cantidad = $entrega_datos['cantidad'];
			$tipo = $entrega_datos['tipo'];
			$entregadas = $entrega_datos['entregadas'];
			//Busco todas las entregas directas correspondientes al padre				
			$entregas_directas = Sds_ent_entrega::getEntregasFinalesTotal($identrega);
			$rendidas = Sds_ent_entrega::getEntregasFinalesTotal(Sds_ent_entrega::getArbolIds($identrega, -1, $externo));
			if ($primer_entrega) :
				$primer_entrega = false;
				$total_entregadas = $total_entregadas + $entregadas;
				$total_rendidas = $total_rendidas + $rendidas;
				$total_stock = $cantidad - $entregadas;
		?>
				<div class="row">
					<div class="col-xs-6">
						<b>Entrega: </b><?= $entrega_datos['emisor']; ?>
					</div>
					<div class="col-xs-4">
						<b>Fecha: </b><?= $fecha_carga; ?>
					</div>
				</div>
				<div class="row" style="padding-bottom: 3%;">
					<div class="col-xs-10">
						<b>Receptor: </b><?= $receptor; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3">
						<b>OC: </b><?= $entrega_datos['oc']; ?>
					</div>
					<div class="col-xs-7">
						<b>Proveedor: </b><?= $entrega_datos['proveedor']; ?>
					</div>
				</div>
				<div class="row" style="padding-bottom: 5%;">
					<div class="col-xs-3">
						<b>Cantidad: </b><?= $cantidad; ?>
					</div>
					<div class="col-xs-7">
						<b>Tipo: </b><?= $entrega_datos['tipo']; ?>
					</div>
				</div>
				<div class="row" style="padding-bottom: 5%;
				<?= $entrega_datos['numero_desde'] != null ? "display:inline;" : "display:none;" ?>">
					<div class="col-xs-10"><b>Numeración: </b>
						<?= $entrega_datos['numero_desde'] != null ?
							("Del " . $entrega_datos['numero_desde'] . " al " . $entrega_datos['numero_hasta']) : "";
						?>
					</div>
				</div>
				<?php if ($entregas_directas > 0) : ?>
					<div class="row" style="padding-bottom: 1%;">
						<div class="col-xs-4">
							<b><?= $entregas_directas ?> entregas </b>directas
						</div>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="row" style="padding-bottom: 1%;">
					<div class="col-xs-5">
						<b><?= $entrega_datos['numero_desde'] != null ?
								("Del " . $entrega_datos['numero_desde']
									. " al " . $entrega_datos['numero_hasta']) . " - " : "";
							?><?= $fecha_carga; ?></b>
						<b> - <?= $cantidad ?></b> a
						<?= $receptor; ?>
					</div>
					<div class="col-xs-5">
						<b>Faltan rendir <?= $cantidad - $rendidas ?></b>
						<b> - En Stock <?= $cantidad - $entregadas ?></b>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($detalle != 'false') :
				$query = new yii\db\Query;
				$query->select([
					"fecha_hora", "cantidad", "dni", "numero",
					"(select concat(apellido,', ',nombre) from sds_com_persona p 
							where p.idpersona=sds_ent_entrega.idpersona) nomap"
				])
					->from(["sds_ent_entrega"])
					->where("dni is not null and emisor=" . $identrega)
					->orderBy(["numero" => SORT_ASC, "fecha_hora" => SORT_ASC]);
				$command = $query->createCommand();
				$entregas_finales = $command->queryAll();
				foreach ($entregas_finales as $ent_final) :
					$fecha_final = $ent_final['fecha_hora'];
					$unafecha = explode(" ", $fecha_final);
					$unafecha = explode("-", $unafecha[0]);
					$fecha_final = $unafecha[2] . "/" . $unafecha[1] . "/" . $unafecha[0];
					$cant_final = $ent_final['cantidad'];
					$dni_final = $ent_final['dni'];
					$nom_ap_final = $ent_final['nomap'];
					$numero = $ent_final['numero'];
			?>
					<div class="col-xs-12">
						<div class="row" style="padding-bottom: 1%;">
							<div class="col-xs-12">
								<b>Nº <?= ($numero != null ? $numero . " - " : "") . $cant_final ?> </b>a
								<?= $nom_ap_final . " | DNI " . $dni_final . " | " . $fecha_final; ?>
							</div>
						</div>
					</div>
			<?php
				endforeach;
			endif;
			?>
		<?php endforeach; ?>
		<h4 style="padding-top: 3%;"><b>Totales:</b></h4>
		<div class="row">
			<div class="col-xs-4">
				<b><?= $total_entregadas ?> entregadas </b>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
				<b><?= $total_rendidas ?> rendidas </b>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
				<b><?= $total_stock ?> en Stock </b>
			</div>
		</div>
	</div>
</body>

</html>