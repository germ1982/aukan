<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE VENCIMIENTOS</h4>
			<p><span> ReProAM </span></p>
			<hr style="margin: 0">
		</div>
		
		<p class="parrafo" colspan="7" style="font-size: 22px; text-align:center;  color:#333; background-color: #a9a9a9;">
			<b>Registros con Personería Jurídica No Vencida</b>
		</p>

		<?php if (count($venproximo) != 0 || count($novencida) != 0): ?>
			
			<?php if (count($venproximo) != 0): ?>
				<table style="page-break-inside:auto">
					<thead style="display:table-header-group">
						<tr>
							<th colspan="7" style="font-size: 18px; padding: 10px 0 20px 0">
								Vence en los proximos 3 meses:
							</th>
						</tr>
						<tr style="page-break-inside:avoid; page-break-after:auto; background-color: #dddddd; text-align:justify">
							<th style="width: 14%">Fecha de Vencimiento</th>
							<th style="width: 11%">N° Legajo </th>
							<th style="width: 13%">Nombre </th>
							<th style="width: 11%">Res. P. Jurídica</th>
							<th style="width: 14%">Presidente</th>
							<th style="width: 14%">Teléfono Fijo</th>
							<th style="width: 14%">Celular</th>
							<th style="width: 23%">Email</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($venproximo as $ven) {?>
						<tr style="page-break-inside:avoid; page-break-after:auto">
							<td valign="top" style="width: 14%" style="font-weight: normal;"><?php
								$fv = date_create($ven['personeria_juridica_fecha_vencimiento']);
								$fv = date_format($fv, 'd-m-Y');
								echo $fv ?>
							</td>
							<td valign="top" style="font-weight: normal;"><?= $ven['numero_legajo_reproam'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['nombre'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['personeria_juridica_resolucion'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['nombre_presidente'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['telefono'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['telefono_movil'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['mail'] ?></td>
						</tr>
						<?php } ?>	
					</tbody>
				</table>
			<?php endif; ?>

			<?php if(count($novencida) != 0): ?>
				<table style="page-break-inside:auto;">
					<thead style="display:table-header-group">
						<tr>
							<th colspan="7" style="font-size: 18px; padding: 20px 0 20px 0">
								Vence en más de 3 meses:
							</th>
						</tr>
						<tr style="page-break-inside:avoid; page-break-after:auto; background-color: #dddddd; text-align:justify">
							<th style="width: 14%">Fecha de Vencimiento</th>
							<th style="width: 11%">N° Legajo </th>
							<th style="width: 13%">Nombre </th>
							<th style="width: 11%">Res. P. Jurídica</th>
							<th style="width: 14%">Presidente</th>
							<th style="width: 14%">Teléfono Fijo</th>
							<th style="width: 14%">Celular</th>
							<th style="width: 23%">Email</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($novencida as $ven) {?>
						<tr style="page-break-inside:avoid; page-break-after:auto">
							<td style="width: 14%" valign="top" style="font-weight: normal;"><?php
								$fv = date_create($ven['personeria_juridica_fecha_vencimiento']);
								$fv = date_format($fv, 'd-m-Y');
								echo $fv ?>
							</td>
							<td valign="top" style="font-weight: normal;"><?= $ven['numero_legajo_reproam'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['nombre'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['personeria_juridica_resolucion'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['nombre_presidente'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['telefono'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['telefono_movil'] ?></td>
							<td valign="top" style="font-weight: normal;"><?= $ven['mail'] ?></td>
						</tr>
						<?php } ?>			
					</tbody>
				</table>
			<?php endif; ?>

		<?php else : ?>
			<p>No hay registros.</p>
		<?php endif; ?>

		<br>

		<p class="parrafo" colspan="7" style="font-size: 22px; text-align:center;  color:#333; background-color: #a9a9a9;">
			<b>Registros con Personería Jurídica Vencida</b>
		</p>

		<?php if (count($vencida) != 0): ?>
			<table style="page-break-inside:auto; margin-top: 20px">
				<thead style="display:table-header-group;">
					<tr style="page-break-inside:avoid; page-break-after:auto; background-color: #dddddd; text-align:justify">
						<th style="width: 14%">Fecha de Vencimiento</th>
						<th style="width: 11%">N° Legajo </th>
						<th style="width: 13%">Nombre </th>
						<th style="width: 11%">Res. P. Jurídica</th>
						<th style="width: 14%">Presidente</th>
						<th style="width: 14%">Teléfono Fijo</th>
						<th style="width: 14%">Celular</th>
						<th style="width: 23%">Email</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($vencida as $ven) {?>
					<tr style="page-break-inside:avoid; page-break-after:auto">
						<td style="width: 14%" valign="top" style="font-weight: normal;"><?php
							$fv = date_create($ven['personeria_juridica_fecha_vencimiento']);
							$fv = date_format($fv, 'd-m-Y');
							echo $fv ?>
						</td>
						<td valign="top" style="font-weight: normal;"><?= $ven['numero_legajo_reproam'] ?></td>
						<td valign="top" style="font-weight: normal;"><?= $ven['nombre'] ?></td>
						<td valign="top" style="font-weight: normal;"><?= $ven['personeria_juridica_resolucion'] ?></td>
						<td valign="top" style="font-weight: normal;"><?= $ven['nombre_presidente'] ?></td>
						<td valign="top" style="font-weight: normal;"><?= $ven['telefono'] ?></td>
						<td valign="top" style="font-weight: normal;"><?= $ven['telefono_movil'] ?></td>
						<td valign="top" style="font-weight: normal;"><?= $ven['mail'] ?></td>
					</tr>
					<?php } ?>			
				</tbody>
			</table>
		<?php else : ?>
			<p class="parrafo" colspan="7" style="font-size: 22px; text-align:center;  color:#333; background-color: #a9a9a9;">
				<b >Registros con Personería Jurídica Vencida </b>
			</p>
			<p>No hay registros.</p>
		<?php endif; ?>
	</div>
</body>

</html>