<?php
$filtro_rubro = '';
$identrega = $_GET['identrega'];
$tienefotofrente = true;
$tienefotodorso = true;
//POR AHORA HARDCODEAR LA LOCALIDAD NEUQUÉN (NO TENEMOS LOCALIDAD, SOLO LATITUD Y LONGITUD)
//NOTIFICADO A JP: 09/03/2020 15:07

/* Consulta SQL:
select identrega,cantidad,(select descripcion
from sds_ent_tipo tipo
where tipo.idtipo=sds_ent_entrega.idtipo),dni,observaciones
from sds_ent_entrega
 */

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
where tipo.idtipo=sds_ent_entrega.idtipo) tipo", "dni", "(select concat(apellido,', ',nombre) from sds_com_persona p where p.idpersona=sds_ent_entrega.idpersona) nomap", "observaciones", "dni_frente", "dni_dorso", "fecha_hora"
])
	->from(["sds_ent_entrega"])
	->where(["identrega" => $identrega]);

$command = $query->createCommand();
$entrega_datos = $command->queryOne();

$fecha_carga = $entrega_datos['fecha_hora'];
$unafecha = explode(" ", $fecha_carga);
$unafecha = explode("-", $unafecha[0]);
$fecha_carga = $unafecha[2] . "/" . $unafecha[1] . "/" . $unafecha[0];


?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
		<div class="row">
			<div class="col-xs-offset-6 col-xs-5" style="text-align: right;">Neuquén, <?= $entrega_datos['fecha_dia'] . ' de ' . $entrega_datos['fecha_mes'] . ' de ' . $entrega_datos['fecha_anio'] . ', ' . date('H:i') . 'hs.';  ?>
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
				<td><b>Documento </b></td>
				<td> <?= $entrega_datos['dni']; ?> </td>
				<td><b>Apellido y Nombre </b></td>
				<td> <?= $entrega_datos['nomap']; ?> </td>
				<td></td>
			</tr>
			<tr>
				<td><b>Tipo de Entrega </b></td>
				<td> <?= $entrega_datos['tipo']; ?> </td>
				<td>&nbsp;&nbsp;&nbsp;<b>Fecha de Carga </b></td>
				<td><?= $fecha_carga; ?> </td>
			</tr>
			<tr>
				<td><b>Cantidad </b></td>
				<td> <?= $entrega_datos['cantidad']; ?> </td>
			</tr>
			<tr>
				<td><b>Detalle </b></td>
				<td colspan="3"> <?= $entrega_datos['observaciones']; ?> </td>
			</tr>
			<?php
			if ($entrega_datos['dni_frente'] == null) {
				$tienefotofrente = false;
				echo '<tr> 
							<td><b>Dni Frente </b></td><td colspan="3"> Sin foto</td>				
					    </tr>';
			}
			?>
			<?php
			if ($entrega_datos['dni_dorso'] == null) {
				$tienefotodorso = false;
				echo '<tr> 
							<td><b>Dni Dorso </b></td><td colspan="3"> Sin foto</td>				
					    </tr>';
			}
			?>
		</table>
		<br>
		<?php

		if ($tienefotofrente || $tienefotodorso) {
			echo '
		<div style="text-align:center;">
			<table  style="margin: 0 auto;">	
				<tr >
					<td td class="desc" style="background-color:#FFFFFF">						
					DNI FRENTE <br>
					<img style="display:block;   height:36%;"  src=';
			if ($tienefotofrente) {
				$url_image = $entrega_datos['dni_frente'];
				echo '"'.$url_image.'" /> ';
			}/*else { echo '"../web/img/dni_sin_foto.png" /> ';}	*/
			echo '					
					</td>
				<tr>
				<tr>
					<td td class="desc" style="background-color:#FFFFFF">
					DNI DORSO <br>   
					<img style="display:block; height:36%;"  src=';

			if ($tienefotodorso) {
				$url_image = $entrega_datos['dni_dorso'];
				echo '"'.$url_image.'" /> ';
			}/*else { echo '"../web/img/dni_sin_foto.png" /> ';}*/
			echo '
					</td>
				</tr>
			</table>
		</div>';
		}

		?>

	</div>
</body><br>
<footer style=" left: 0;
  bottom: 10%;
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