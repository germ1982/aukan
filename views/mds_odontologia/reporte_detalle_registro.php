<html>
<?php
function calculaedad($fechanacimiento)
{
	$data_birth = new DateTime($fechanacimiento); //Crea el objeto DateTime a partir de un string de fecha
	$data_hoy = new DateTime(); //devuelve la fecha actual
	$edad = $data_birth->diff($data_hoy); //Aplicamos la diferencia entre fechas
	$edad = $edad->y;
	return $edad;
}
?>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE ODONTOLOGÍA</h4>
			<p><span>Programa odontológico</span></p>
			<hr style="margin: 0 0 20px 0">
		</div>
		<table>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5><?= "{$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})" ?></h5>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 50%;">
					<b>Teléfono: </b><span><?= $model->telefono ? $model->telefono : '' ?></span>
				</td>
				<td valign="top" style="width: 50%;">
					<b>Fecha de Nacimiento: </b><span>
						<?php
						$fn = date_create($model->persona->fecha_nacimiento);
						$fn = date_format($fn, 'd-m-Y');
						$edad = calculaedad($model->persona->fecha_nacimiento);
						$edad_imprimir = $edad == 1 ? 'año' : 'años';
						echo "{$fn} ({$edad} {$edad_imprimir}) "
						?>
					</span>
				</td>
			</tr>
		</table>
		<hr>
		<table>
			<tr>
				<th>
					<b>Vacunas</b>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 50%;">
					<b>Vacunas obligatorias: </b><span><?= ($model->vacunas_obligatorias == 1) ? 'Si' : ($model->vacunas_obligatorias == 0 ? 'No' : 'Se desconoce') ?></span>
				</td>
				<td valign="top" style="width: 50%;">
					<b>COVID-19: </b><span><?= ($model->vacunacovid19) ? $model->vacunacovid19->descripcion : '' ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table>
			<tr>
				<th>
					<b>Atención</b>
				</th>
			</tr>
			<tr>
				<td colspan="2">
					<b>Usuario de carga: </b><span><?= "{$model->usuariocarga->apellido} {$model->usuariocarga->nombre} " ?></span>
				</td>
				<td colspan="2">
					<b>Fecha de carga: </b>
					<span>
						<?php
						$fc = date_create($model->created_at);
						$fc = date_format($fc, 'd-m-Y');
						echo $fc;
						?>
					</span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<b>Tipo de intervención: </b><span><?= "{$model->tipointervencion->descripcion}" ?></span>
				</td>
				<td colspan="2">
					<b>Fecha de atención: </b><span>
						<?php
						$fa = date_create($model->fecha_atencion);
						$fa = date_format($fa, 'd-m-Y');
						echo $fa
						?>
					</span>
				</td>
			</tr>
			<?php
			if ($model->dispositivo) {
			?>
				<tr>
					<td>
						<b>Institución/Dispositivo: </b>
						<span>
							<?= ($model->dispositivo->descripcion ? "{$model->dispositivo->descripcion}" : ""); ?>
						</span>
					</td>
				</tr>
			<?php } ?>
			<?php
			if ($model->escolaridad) {
			?>
				<tr>
					<td colspan="2">
						<b>Escolaridad: </b>
						<span>
							<?= ($model->escolaridad->descripcion ? "{$model->escolaridad->descripcion}" : ""); ?>
						</span>
					</td>
				</tr>
			<?php } ?>
			<?php
			if ($model->idtipovisita) {
			?>
				<tr>
					<td>
						<b>Tipo de visita: </b>
						<span>
							<?= ($model->tipovisita->descripcion ? "{$model->tipovisita->descripcion}" : ""); ?>
						</span>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td>
					<b>Dientes permanentes</b>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Cantidad de dientes: <span><?= "{$model->cant_dientes} " ?></span>
				</td>
				<td colspan="2">
					Cantidad de caries: <span><?= "{$model->cant_caries}" ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Cantidad de obturados: <span><?= "{$model->cant_obturados} " ?></span>
				</td>
				<td colspan="2">
					Cantidad de perdidos: <span><?= "{$model->cant_perdidos}" ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Dientes temporales</b>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Cantidad de dientes: <span><?= "{$model->cant_dientes_temporales} " ?></span>
				</td>
				<td colspan="2">
					Cantidad de caries: <span><?= "{$model->cant_caries_temporales}" ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Cantidad de obturados: <span><?= "{$model->cant_obturados_temporales} " ?></span>
				</td>
				<td colspan="2">
					Cantidad de perdidos: <span><?= "{$model->cant_perdidos_temporales}" ?></span>
				</td>
			</tr>

			<?php
			if ($model->enfermedad_periodontal) {
			?>
				<tr>
					<td>
						<b>Enfermedad periodontal: </b>
						<span>
							<?= ($model->enfermedad_periodontal ? "{$model->enfermedad_periodontal}" : ""); ?>
						</span>
					</td>
				</tr>
			<?php } ?>
			<?php
			if ($model->enfermedad_base) {
			?>
				<tr>
					<td>
						<b>Enfermedad base: </b>
						<span>
							<?= ($model->enfermedad_base ? "{$model->enfermedad_base}" : ""); ?>
						</span>
					</td>
				</tr>
			<?php } ?>
		</table>
		<table>
			<tr>
				<td>
					<b>Observaciones: </b><span><?= $model->observaciones ? $model->observaciones : '' ?></span>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td>
					<b>Posee documentación adjunta: </b><span><?= count($model->getAdjuntos()) > 0 ? 'Si' : 'No' ?></span>
				</td>
			</tr>
		</table>
		<hr style="margin: 25px 0 0 0">
	</div>
</body>

</html>