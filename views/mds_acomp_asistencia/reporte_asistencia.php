<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE ASISTENCIAS</h4>
			<p><span> PROGRAMA ACOMPAÑAR </span></p>
			<hr style="margin: 0 0 20px 0">
		</div>
		<?php if(count($arrayAsistencias) != 0 ): ?>
			<table cellpadding="7" cellspacing="0" >
				<thead style="display:table-header-group">
					<tr style="background-color: #dddddd; text-align:justify">
						<th style="width: 5%">#</th>
						<th style="width: 19%">Beneficiario</th>
						<th style="width: 13%">Localidad</th>
						<th style="width: 13%">Localidad ingreso</th>
						<th style="width: 10%">Riesgo</th>
						<th style="width: 13%">Perido Desde</th>
						<th style="width: 13%">Perido Hasta</th>
						<th style="width: 14%">Usuario de carga</th>
					</tr>
				</thead>
				<?php foreach ($arrayAsistencias as $asistencia) {
					$apellido = mb_strtoupper($asistencia->beneficiario->apellido);
					$nombre = mb_strtoupper($asistencia->beneficiario->nombre);
					$usuarioCargaApellido = mb_strtoupper($asistencia->usuarioCarga->apellido);
					$usuarioCargaNombre = mb_strtoupper($asistencia->usuarioCarga->nombre);
				?>
				<tr>
					<td valign="top" style="font-weight: normal !important;"><?= $asistencia->idasistencia ?></td>
					<td valign="top" style="font-weight: normal !important;"><?= "$apellido, $nombre ({$asistencia->beneficiario->documento})"?></td>
					<td valign="top" style="font-weight: normal !important;"><?= $asistencia->localidad['descripcion'] ?></td>
					<td valign="top" style="font-weight: normal !important;"><?= $asistencia->localidadIngreso['descripcion'] ?></td>
					<td valign="top" style="font-weight: normal !important;"><?= $asistencia->riesgo['descripcion'] ?></td>
					<td valign="top" style="font-weight: normal !important;"><?php
						$fv = date_create($asistencia['periodo_desde']);
						$fv = date_format($fv, 'd-m-Y');
						echo $fv ?>
					</td>
					<td valign="top" style="font-weight: normal !important;"><?php
						$fv = date_create($asistencia['periodo_hasta']);
						$fv = date_format($fv, 'd-m-Y');
						echo $fv ?>
					</td>
					<td valign="top" style="font-weight: normal !important;"><?= "$usuarioCargaApellido, $usuarioCargaNombre" ?></td>
				</tr>
				<?php } ?>		
			</table>
		<?php else: ?>
			<p>No hay asistencias.</p>
		<?php endif; ?>
	</div>
</body>

</html>