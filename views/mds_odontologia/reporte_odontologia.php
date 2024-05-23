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
			<p><span> PROGRAMA ODONTOLÓGICO </span></p>
			<hr style="margin: 0 0 20px 0">
		</div>
		<?php if (count($arrayAsistencias) != 0) : ?>
			<?php foreach ($arrayAsistencias as $asistencia) { ?>
				<table>
					<tr style="background-color: #dddddd;">
						<th class="titulo" colspan="2">
							<h5>DATOS DEL REGISTRO: <?= "{$asistencia->persona->apellido} {$asistencia->persona->nombre} ({$asistencia->persona->documento})" ?></h5>
						</th>
					</tr>
					<tr>
						<td valign="top" style="width: 50%">
							<b>Teléfono: </b><span><?= "{$asistencia->telefono}" ?></span>
						</td>
						<td valign="top" style="width: 50%;">
							<b>Fecha de Nacimiento: </b><span>
								<?php
								$fn = date_create($asistencia->persona->fecha_nacimiento);
								$fn = date_format($fn, 'd-m-Y');
								$edad = calculaedad($asistencia->persona->fecha_nacimiento);
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
						<td valign="top" style="width: 50%">
							<b>Vacunas obligatorias: </b>
							<span>
								<?php
								echo ($asistencia->vacunas_obligatorias == 1 ? "Si" : "No");
								?>
							</span>
						</td>
						<?php
						if ($asistencia->vacunacovid19) {
						?>
							<td valign="top" style="width: 50%">
								<b>Vacuna COVID19: </b>
								<span>
									<?php
									echo ($asistencia->vacunacovid19->descripcion ? "{$asistencia->vacunacovid19->descripcion}" : "");
									?>
								</span>
							</td>
					</tr>
				<?php } ?>
				</table>
				<hr>
				<table>
					<tr>
						<th>
							<b>Atención</b>
						</th>
					</tr>
					<tr>
						<td valign="top" style="width: 50%">
							<b>Usuario de carga: </b><span><?= "{$asistencia->usuariocarga->apellido} {$asistencia->usuariocarga->nombre} " ?></span>
						</td>
						<td valign="top" style="width: 50%">
							<b>Fecha de carga: </b>
							<span>
								<?php
								$fv = date_create($asistencia->created_at);
								$fv = date_format($fv, 'd-m-Y');
								echo $fv
								?>
							</span>
						</td>
					</tr>
					<tr>
						<td valign="top" style="width: 50%">
							<b>Tipo de intervención: </b><span><?= "{$asistencia->tipointervencion->descripcion}" ?></span>
						</td>
						<td valign="top" style="width: 50%">
							<b>Fecha de atención: </b><span><?= date('d/m/Y', strtotime(str_replace('/', '-', $asistencia->fecha_atencion))) ?></span>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<b>Dientes permanentes</b>
						</td>
					</tr>
					<tr>
						<td valign="top" style="width: 50%">
							Cantidad de dientes: <span><?= "{$asistencia->cant_dientes}" ?></span>
						</td>
						<td valign="top" style="width: 50%">
							Cantidad de caries: <span><?= "{$asistencia->cant_caries}" ?></span>
						</td>
					</tr>
					<tr>
						<td valign="top" style="width: 50%">
							Cantidad de obturados: <span><?= "{$asistencia->cant_obturados}" ?></span>
						</td>
						<td valign="top" style="width: 50%">
							Cantidad de perdidos: <span><?= "{$asistencia->cant_perdidos}" ?></span>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<b>Dientes temporales</b>
						</td>
					</tr>
					<tr>
						<td valign="top" style="width: 50%">
							Cantidad de dientes: <span><?= "{$asistencia->cant_dientes_temporales}" ?></span>
						</td>
						<td valign="top" style="width: 50%">
							Cantidad de caries: <span><?= "{$asistencia->cant_caries_temporales}" ?></span>
						</td>
					</tr>
					<tr>
						<td valign="top" style="width: 50%">
							Cantidad de obturados: <span><?= "{$asistencia->cant_obturados_temporales}" ?></span>
						</td>
						<td valign="top" style="width: 50%">
							Cantidad de perdidos: <span><?= "{$asistencia->cant_perdidos_temporales}" ?></span>
						</td>
					</tr>
				</table>
				<table>
					<?php
					if ($asistencia->enfermedad_periodontal) {
					?>
						<tr>
							<td valign="top" colspan="2">
								<b>Enfermedad periodontal: </b>
								<span>
									<?php
									echo ($asistencia->enfermedad_periodontal ? "{$asistencia->enfermedad_periodontal}" : "");
									?>
								</span>
							</td>
						</tr>
					<?php } ?>
					<?php
					if ($asistencia->enfermedad_base) {
					?>
						<tr>
							<td valign="top" colspan="2">
								<b>Enfermedad base: </b>
								<span>
									<?php
									echo ($asistencia->enfermedad_base ? "{$asistencia->enfermedad_base}" : "");
									?>
								</span>
							</td>
						</tr>
					<?php } ?>
				</table>
				<table>
					<tr>
						<td valign="top" colspan="2" style="text-align: justify"><b>Observación: </b><span><?= $asistencia->observaciones ?></span></td>
					</tr>
				</table>
				<table>
					<tr>
						<td>
							<b>Posee documentación adjunta: </b><span><?= count($asistencia->getAdjuntos()) > 0 ? 'Si' : 'No' ?></span>
						</td>
					</tr>
				</table>
				<br>
			<?php } ?>
		<?php else : ?>
			<p>No hay registros.</p>
		<?php endif; ?>
	</div>
</body>

</html>