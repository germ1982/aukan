<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="https://desasur.neuquen.gov.ar/familia/web/img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;"><?= $title ?></h4>
			<p><span> Certificaciones </span></p>
			<hr style="margin: 0 0 20px 0">
		</div>


		<?php if (count($model) != 0) : ?>

			<table cellpadding="10" cellspacing="0">
				<tr style="background-color: #dddddd; text-align: justify">
					<thead style="display:table-header-group">
						<th style="width: 4%">#</th>
						<th style="width: 11%">Beneficiario</th>
						<th style="width: 10%">Programa</th>
						<th style="width: 10%">Dirección</th>
						<th style="width: 10%">Monto</th>
						<th style="width: 10%">Responsable de cobro/Tutor especial</th>
						<th style="width: 10%">N° Exp. <br> N° Nota</th>
						<th style="width: 10%">Localidad</th>
						<th style="width: 10%">Periodo</th>
						<th style="width: 10%">Estado</th>

					</thead>
				</tr>
				<?php foreach ($model as $certificacion) {
					$data = " {$certificacion['apellido']} {$certificacion['nombre']} ({$certificacion['documento']})";
					$responsable = '';
					if (!empty($certificacion['responsable'])) {
						$responsable = "{$certificacion['responsable']}";
					};
					if (!empty($certificacion['dni'])) {
						$responsable = $responsable . " ({$certificacion['dni']})";
					};

					$fecha_desde = date_create($certificacion['periodo_desde']);
					$fecha_desde = date_format($fecha_desde, 'd-m-Y');

					$fecha_hasta = date_create($certificacion['periodo_hasta']);
					$fecha_hasta = date_format($fecha_hasta, 'd-m-Y');
				?>
					<tr>
						<td valign="top"><?= $certificacion['id_certificacion'] ?></td>
						<td valign="top"><?= strtoupper($data) ?></td>
						<td valign="top"><?= $certificacion['programa'] ?></td>
						<td valign="top"><?= $certificacion['direccion'] ?></td>
						<td valign="top">$<?= $certificacion['monto'] ?></td>
						<td valign="top"><?= $responsable ?></td>
						<td valign="top"><?= ($certificacion['nro_expediente'] ? 'N° Exp. ' . $certificacion['nro_expediente'] : '') ?><br><?= ($certificacion['nro_nota'] ? 'N° Nota ' . $certificacion['nro_nota'] : '') ?></td>
						<td valign="top"><?= $certificacion['localidadDescripcion'] ? $certificacion['localidadDescripcion'] : '' ?></td>
						<td valign="top"><?= $fecha_desde ?> <?= $fecha_hasta ?></td>
						<td valign="top"><?= $certificacion['estado'] ?></td>
					</tr>
				<?php } ?>
			</table>
		<?php else : ?>
			<p>No hay certificaciones.</p>
		<?php endif; ?>

	</div>
</body>

</html>