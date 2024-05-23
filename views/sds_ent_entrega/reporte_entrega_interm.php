<?php
$filtro_rubro = '';
$identrega = $_GET['identrega'];

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
where conf.idconfiguracion=(select receptor from sds_ent_entrega entemisor where entemisor.identrega=sds_ent_entrega.emisor)),'Primer Ingreso') emisor",
	"conf.descripcion receptor", "responsable.dni", "responsable.mail", "responsable.telefono", "responsable.dni_frente", "responsable.dni_dorso", "observaciones", "fecha_hora",
	"ifnull((select rendiciones_pendientes from sds_ent_solicitud_intermedia solic 
	where solic.idsolicitudintermedia=sds_ent_entrega.idsolicitudintermedia),'') pendientes",
	"(select concat(apellido,', ',nombre) from sds_com_persona p where p.idpersona=sds_ent_entrega.persona_retira) retira,
(select concat(p.apellido,', ',p.nombre)
from sds_com_persona p,mds_org_contacto c,mds_seg_usuario u
where p.idpersona=c.idpersona and u.idcontacto=c.idcontacto and u.idusuario=sds_ent_entrega.usuario_entrega) entrega ",
	"numero_desde", "numero_hasta"
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
$tienefotofrente = true;
$tienefotodorso = true;

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
		<div class="row" style="padding-top: 2%;">
			<div class="col-xs-offset-6 col-xs-5" style="text-align: right;">Neuquén, <?= $entrega_datos['fecha_dia'] . ' de ' .
																							$entrega_datos['fecha_mes'] . ' de ' . $entrega_datos['fecha_anio'] . ', ' . date('H:i') . 'hs.';  ?>
			</div>
		</div>
		<div class="row" style="text-align:center;padding-top: 2%;padding-bottom: 2%;font-size: 12pt;">
			<div class="col-xs-12">
				<b>Acta de Entrega Nº <?= $identrega; ?></b><br>
				Ministerio de Desarrollo Social y Trabajo
			</div>
		</div>
		<table cellspacing="10">
			<tr>
				<td><b>Emisor </b></td>
				<td> <?= $entrega_datos['emisor']; ?> </td>
				<td></td>
			</tr>
			<tr>
				<td><b>Receptor </b></td>
				<td> <?= $entrega_datos['receptor']; ?> </td>
				<td>&nbsp;&nbsp;&nbsp;<b>DNI </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?= $entrega_datos['dni'] != null ? $entrega_datos['dni'] : "_______________________"; ?></td>
			</tr>
			<tr>
				<td><b>Email </b></td>
				<td> <?= $entrega_datos['mail'] != null ? $entrega_datos['mail'] : "_____________________________"; ?> </td>
				<td><b>&nbsp;&nbsp;&nbsp;Teléfono </b>&nbsp;&nbsp;&nbsp;<?= $entrega_datos['telefono'] != null ? $entrega_datos['telefono'] : "_______________________"; ?></td>
			</tr>
			<tr>
				<td style="padding-top: 10px;"><b>Tipo de Entrega </b></td>
				<td style="padding-top: 10px;"> <?= $entrega_datos['tipo']; ?> </td>
				<td style="padding-top: 10px;">&nbsp;&nbsp;&nbsp;<b>Fecha de Entrega </b><?= $fecha_carga; ?></td>
			</tr>
			<tr>
				<td><b>Cantidad </b></td>
				<td> <?= $entrega_datos['cantidad']; ?> </td>
			</tr>
			<tr>
				<td><b><?= $entrega_datos['observaciones'] != "" ? "Detalle" : ($entrega_datos['numero_desde'] != null ? "Numeración" : "") ?> </b></td>
				<td colspan="3"> <?= $entrega_datos['observaciones'] != "" ?
										$entrega_datos['observaciones']
										: ($entrega_datos['numero_desde'] != null ?
											("Del " . $entrega_datos['numero_desde'] . " al "
												. $entrega_datos['numero_hasta']) : ""); ?></td>
			</tr>
			<?php
			echo '<tr>';
			if ($entrega_datos['dni_frente'] == null) {
				$tienefotofrente = false;
				echo '<td><b>Dni Frente </b></td><td> Sin foto</td>';
			}
			?>
			<?php
			if ($entrega_datos['dni_dorso'] == null) {
				$tienefotodorso = false;
				echo '<td>&nbsp;&nbsp;&nbsp;<b>Dni Dorso&nbsp;&nbsp;&nbsp;</b>Sin foto</td>';
			}
			echo '</tr>';
			?>
		</table>
		<?php

		if ($tienefotofrente || $tienefotodorso) {
			echo '
				<div style="text-align:center;">
					<table style="margin: 0 auto;">	
						<tr >
							<td style="background-color:#FFFFFF">						
								DNI FRENTE <br>
								<img style="display:block;   height:200px;"  src=';
			if ($tienefotofrente) {
				$url_image = $entrega_datos['dni_frente'];
				echo '"' . $url_image . '" /> ';
			}/*else { echo '"../web/img/dni_sin_foto.png" /> ';}	*/
			echo '					
							</td>
							<td style="background-color:#FFFFFF">
								DNI DORSO <br>   
								<img style="display:block; height:200px;"  src=';

			if ($tienefotodorso) {
				$url_image = $entrega_datos['dni_dorso'];
				echo '"' . $url_image . '" /> ';
			}/*else { echo '"../web/img/dni_sin_foto.png" /> ';}*/
			echo '
							</td>
						</tr>
					</table>
				</div>';
		}

		?>
	</div>

	<?php

	?>

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
	<?php if ($entrega_datos['pendientes'] != "") : ?>
		<div style="font-size: 9pt;">
			<div class="row">
				<div class="col-xs-12">
					<b>Faltan Rendir: </b>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<?= $entrega_datos['pendientes']; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<br><br><br>
	<div class="row">
		<div class="col-xs-5">
			<b>Entrega </b><?= $entrega_datos['entrega'] != null ? $entrega_datos['entrega'] : "_______________________"; ?>
		</div>
		<div class="col-xs-5">
			<b>Retira </b><?= $entrega_datos['retira'] != null ? $entrega_datos['retira'] : "_______________________"; ?>
		</div>
	</div>
	<br><br>
	<p style="font-size: 8pt;border:3px; border-style: double; border-color:#1A1A1A; padding: 5px;">
		Se informa que de acuerdo a lo Reglamentado en el CAPITULO II, Art.81, de la Ley de Administración Financiera y Control
		N° 2141, Ud., dispone de 10 (diez) días para proceder a realizar la RENDICIÓN con fotocopia de DNI legible y firma del beneficiario,
		de la mercadería que le es entregada con la presente Acta.
		<br>
		<b>Se enviarán los datos para realizar la rendición a la casilla de mail especificada.<b>
	</p>
</footer>

</html>