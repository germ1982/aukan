<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="https://desasur.neuquen.gov.ar/familia/web/img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE CERTIFICACIÓN</h4>
			<hr>
		</div>
		<?php
		$size = count($model);
		$pages = 1;
		?>

		<?php foreach ($model as $certificacion) { ?>
			<table>
				<tr style="background-color: #dddddd;">
					<th class="titulo">
						<h5>DATOS DE CERTIFICACIÓN #<?= $certificacion['datos']['idcertificacion'] ?>: </h5>
					</th>
				</tr>
				<tr>
					<td valign="top"><b>Beneficiario: </b><span><?= $certificacion['datos']['beneficiario'] ?></span></td>
					<td valign="top"><b>Localidad: </b><span><?= $certificacion['datos']['localidadDescripcion'] ?></span></td>
				</tr>
				<tr>
					<td valign="top"><b>¿Recibe jubilación/pensión?: </b><span><?= $certificacion['datos']['jubilacion'] === null ? '-Sin datos-' : ($certificacion['datos']['jubilacion'] == 1 ? 'Sí' : 'No') ?></span></td>
					<?php if ($certificacion['datos']['jubilacion'] == 1) { ?>
						<td valign="top"><b>Tipo de jubilación/pensión: </b><span><?= $certificacion['datos']['tipoJubilacion'] ?></span></td>
					<?php } ?>
				</tr>
				<tr>
					<?php if ($certificacion['datos']['monto_jubilacion']) { ?>
						<td valign="top"><b>Monto Neto de la jubilación/pensión: </b><span>$<?= $certificacion['datos']['monto_jubilacion'] ?></span></td>
					<?php } ?>
				</tr>
				<tr>
					<td valign="top"><b>¿Recibe sueldo?: </b><span><?= $certificacion['datos']['sueldo'] === null ? '-Sin datos-' : ($certificacion['datos']['sueldo'] == 1 ? 'Sí' : 'No') ?></span></td>
					<?php if ($certificacion['datos']['sueldo_monto']) { ?>
						<td valign="top"><b>Monto Neto del sueldo: </b><span>$<?= $certificacion['datos']['sueldo_monto'] ?></span></td>
					<?php } ?>
				</tr>
			</table>

			<hr style="margin: 10px 0 10px 0">

			<table>
				<tr>
					<th>
						<b>Responsable de cobro/Tutor especial</b>
					</th>
				</tr>
				<tr>
					<td valign="top"><b>¿Es Curador/Tutor Legal?: </b><span><?= $certificacion['datos']['curador_legal'] === null ? '-Sin datos-' : ($certificacion['datos']['curador_legal']  == 1 ? 'Sí' : 'No') ?></span></td>
				</tr>
				<tr>
					<td valign="top"><b>Tipo de responsable de cobro/Tutor especial: </b><span><?= $certificacion['datos']['tipoResponsable'] === null ? '-Sin datos-' : ($certificacion['datos']['tipoResponsable']) ?></span></td>
				</tr>
				<tr>
					<td valign="top"><b>¿Debe presentar la rendición?: </b><span><?= $certificacion['datos']['rendicion'] === null ? '-Sin datos-' : ($certificacion['datos']['rendicion']  == 1 ? 'Sí' : 'No') ?></span></td>
				</tr>
				<tr>
					<td valign="top"><b>Nombre y apellido: </b><span><?= $certificacion['datos']['responsable'] ?></span></td>
					<td valign="top"><b>DNI: </b><span><?= $certificacion['datos']['responsable_dni'] ?></span></td>
				</tr>
				<tr>
					<td valign="top"><b>CBU/ALIAS: </b><span><?= $certificacion['datos']['cbu_alias'] === null ? '' : ($certificacion['datos']['cbu_alias']) ?></span></td>
				</tr>
				<tr>
					<td valign="top"><b>Parentesco: </b><span><?= $certificacion['datos']['parentescoResponsable'] === null ? '' : ($certificacion['datos']['parentescoResponsable']) ?></span></td>
					<?php if ($certificacion['datos']['idparentesco']  == $PARENTESCO_OTRO_OPTION) { ?>
						<td valign="top"><b>Parentesco otro: </b><span><?= $certificacion['datos']['parentesco_otro']  ?></span></td>
					<?php } ?>
				</tr>
				<?php if (count($certificacion['responsables']) > 1) { ?>
					<br />
					<br />
					<tr>
						<th>Historial de responsables</th>
					</tr>
				<?php } ?>

				<?php foreach ($certificacion['responsables'] as $clave => $responsable) {
					if ($clave > 0) { ?>
						<tr>
							<td valign="top">
								<p><b>Responsable: </b><?= $responsable['nombre_apellido'] ?></p>
								<p>Desde <?= $responsable['created_at']; ?><?= $responsable['deleted_at'] ? ', hasta ' . $responsable['deleted_at'] : ' hasta la actualidad' ?></p>
								<p>Motivo: <?= $responsable['motivo_cambio'] ?></p>
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
			</table>
			<hr style="margin: 10px 0 10px 0">
			<table>
				<tr>
					<th>
						<b>Asistencia</b>
					</th>
				</tr>
				<tr>
					<td colspan="2" style="text-align:justify" valign="top"><b>Equipo Técnico: </b><span><?= $certificacion['datos']['equipo_tecnico'] ?></span></td>
				</tr>
				<tr>
					<td valign="top">
						<b>Periodo Desde: </b><span><?= $certificacion['datos']['fecha_desde'] ?></span>
					</td>
					<td valign="top">
						<b>Periodo Hasta: </b><span><?= $certificacion['datos']['fecha_hasta'] ?></span>
					</td>
				</tr>
				<tr>
					<td valign="top"><b>Área: </b><span><?= $certificacion['datos']['area'] ?></span></td>
					<td valign="top"><b>Programa: </b><span><?= $certificacion['datos']['programa'] ?></span></td>
				</tr>
				<tr>
					<td valign="top"><b>Dirección: </b><span><?= $certificacion['datos']['direccion'] ? $certificacion['datos']['direccion'] : '' ?></span></td>
					<td valign="top"></td>
				</tr>
				<tr>
					<td valign="top"><b>Nro Expediente: </b><span><?= $certificacion['datos']['nro_expediente'] ?></span></td>
					<td valign="top"><b>Nro Nota: </b><span><?= $certificacion['datos']['nro_nota'] ?></span></td>
				</tr>
				<tr>
					<td valign="top"><b>Monto: </b><span>$<?= $certificacion['datos']['monto'] ?></span></td>
				</tr>
				<?php if (count($certificacion['montos']) > 1) { ?>
					<br />
					<br />
					<tr>
						<th>Historial de cambio de monto:</th>
					</tr>
				<?php } ?>

				<?php foreach ($certificacion['montos'] as $clave => $monto) {
					if ($clave > 0) { ?>
						<tr>
							<td valign="top">
								<p><b>Monto: </b>$<?= $monto['monto'] ?></p>
							</td>
							<td valign="top">
								<p><b>Periodo: </b>Desde <?= $monto['created_at']; ?><?php echo $monto['deleted_at'] ? ', hasta ' . $monto['deleted_at'] : ', hasta la actualidad' ?></p>
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
				<tr>
					<td valign="top"><b>Carácter: </b><span><?= $certificacion['datos']['caracter'] ?></span></td>
					<?php if ($certificacion['datos']['id_certificacion_incremento']) { ?>
						<td valign="top"><b>Certificación que incrementa </b><span>#<?= $certificacion['datos']['id_certificacion_incremento'] ?></span></td>
					<?php } ?>
				</tr>
				<tr>
					<td valign="top"><b>Tipo de Certificación: </b><span><?= $certificacion['datos']['tipo_certificacion'] === null ? '' : ($certificacion['datos']['tipo_certificacion'] == 1 ? 'EXTERNA' : 'INTERNA') ?></span></td>
					<?php if ($certificacion['datos']['tipo_certificacion'] == 1) { ?>
						<td valign="top"><b>Organismo Solicitante: </b><span><?= $certificacion['datos']['organismoSolicitante'] ? $certificacion['datos']['organismoSolicitante'] : '' ?></span></td>
					<?php } ?>
				</tr>
				<tr>
					<td colspan="2" style="text-align:justify" valign="top"><b>Observación: </b><span><?= $certificacion['datos']['observaciones'] ?></span></td>
				</tr>
				<tr>
					<td>
						<b>Documentación adjunta: </b>
					</td>
				</tr>
				<?php foreach ($certificacion['adjuntos'] as $archivo) { ?>
					<tr>
						<td valign="top">
							<ul>
								<li>
									<?= $archivo['descripcion'] ?>
								</li>
							</ul>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td colspan="2">
						<b>Estado: </b><span><?= $certificacion['estado']['estado'] ? $certificacion['estado']['estado']  : '' ?></span>, actualizado por <span><?= mb_strtoupper($certificacion['estado']['usuario'] ? $certificacion['estado']['usuario'] : '') ?>
					</td>
				</tr>
				<?php if ($certificacion['estado']['fecha'] != '') { ?>
					<tr>
						<td colspan="2">
							Fecha de baja: <span><?= $certificacion['estado']['fecha'] ? $certificacion['estado']['fecha']  : '' ?></span>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<?php if ($certificacion['estado']['observaciones'] != '') { ?>
						<td>
							Detalle: <span><?= $certificacion['estado']['observaciones'] ? $certificacion['estado']['observaciones']  : '' ?></span>
						</td>
					<?php } ?>

				</tr>
				<?php if ($certificacion['adjunto_especial']) { ?>
					<tr>
						<td>
							Posee documentación adjunta
						</td>
					</tr>
				<?php } ?>
			</table>

			<hr style="margin: 10px 0 10px 0">

			<?php if ($pages < $size) { ?>
				<div class="saltopagina"></div>
			<?php }
			$pages++;
			?>

		<?php } ?>
	</div>
</body>

</html>