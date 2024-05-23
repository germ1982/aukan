<?php 
$beneficiarioApellido = mb_strtoupper($model->beneficiario->apellido);
$beneficiarioNombre = mb_strtoupper($model->beneficiario->nombre);
$usuarioCargaApellido = mb_strtoupper($model->usuarioCarga->apellido);
$usuarioCargaNombre = mb_strtoupper($model->usuarioCarga->nombre);
?>

<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE ASISTENCIA</h4>
			<p><span> PROGRAMA ACOMPAÑAR </span></p>
			<hr style="margin: 0 0 20px 0">
		</div>
		<table>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5>DATOS DE LA ASISTENCIA #<?= $model->idasistencia ?> </h5>
				</th>
			</tr>
			<tr>
				<td valign="top" colspan="2">
					<b>Beneficiario: </b><span><?= "$beneficiarioApellido, $beneficiarioNombre ({$model->beneficiario->documento})" ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" style="width: 50%">
					<b>Localidad: </b><span><?= $model->localidad['descripcion'] ?></span>
				</td>
				<td valign="top" style="width: 50%">
					<b>Localidad ingreso: </b><span><?= $model->localidadIngreso['descripcion'] ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" style="width: 50%">
					<b>Periodo Desde: </b>
					<span>
						<?php
						$fv = date_create($model->periodo_desde);
						$fv = date_format($fv, 'd-m-Y');
						echo $fv
						?>
					</span>
				</td>
				<td valign="top" style="width: 50%">
					<b>Periodo Hasta: </b>
					<span>
						<?php
						$fv = date_create($model->periodo_hasta);
						$fv = date_format($fv, 'd-m-Y');
						echo $fv
						?>
					</span>
				</td>
			</tr>
			<tr>
				<td valign="top" style="width: 50%">
					<b>Riesgo: </b><span><?= $model->riesgo['descripcion'] ?></span>
				</td>
				<td valign="top" style="width: 50%">
					<b>Usuario de carga: </b><span><?= "$usuarioCargaApellido, $usuarioCargaNombre" ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="2" style="text-align: justify"><b>Observación: </b><span ><?= $model->observaciones ?></span></td>
			</tr>
		</table>
			<hr style="margin: 25px 0 0 0">
	</div>
</body>

</html>