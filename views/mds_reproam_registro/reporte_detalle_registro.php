<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE REGISTRO</h4>
			<p><span> ReProAM </span></p>
			<hr style="margin: 0">
		</div>
		<table>

			<tr>
				<th><br></th>
			</tr>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5>DATOS DEL REGISTRO #<?= $model->idregistro ?> </h5>
				</th>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>N° de Legajo: </b><span><?= $model->numero_legajo_reproam ?></span></td>
				<td style="width: 50%" valign="top"><b>Nombre: </b><span><?= $model->nombre ?></span></td>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>Localidad: </b><span><?= $model->localidad['descripcion'] ?></span></td>
				<td style="width: 50%" valign="top"><b>Barrio: </b><span><?= $model->barrio['nombre'] ?></span></td>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>Zona: </b><span><?= $model->zona['descripcion'] ?></span></td>
				<td style="width: 50%" valign="top"><b>Dirección: </b><span><?= $model->direccion ?></span></td>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>Situación Habitacional de la Organización/Grupo: </b><span><?= $model->situacionHabitacional ? $model->situacionHabitacional->descripcion : '' ?></span></td>
				<td style="width: 50%" valign="top"><b>Email: </b><span><?= $model->mail ?></span></td>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>Teléfono Fijo: </b><span><?= $model->telefono ?></span></td>
				<td style="width: 50%" valign="top"><b>Celular: </b><span><?= $model->telefono_movil ?></span></td>
			</tr>
			<tr>
				<td colspan="2">
					<hr style="margin: 0">
				</td>
			</tr>
		</table>

		<table>
			<tr>
				<td valign="top" colspan="2"><b>Presidente: </b><span><?= $model->nombre_presidente ?></span></td>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>Vicepresidente: </b><span><?= $model->nombre_vicepresidente ?></span></td>
				<td style="width: 50%" valign="top"><b>Secretario: </b><span><?= $model->nombre_secretario ?></span></td>
			</tr>
			<tr>
				<td colspan="2">
					<hr style="margin: 0">
				</td>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>Personería Jurídica: </b>
					<span>
						<?php if ($model->personeria_juridica == 1) { ?> Si <?php } else { ?> No<?php } ?>
					</span>
				</td>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>Resolución P. Jurídica: </b><span><?= $model->personeria_juridica_resolucion ?></span></td>
				<td style="width: 50%" valign="top"><b>Fecha Vencimiento P. Jurídica: </b>
					<span>
						<?php
						if ($model->personeria_juridica_fecha_vencimiento) {
							$fv = date_create($model->personeria_juridica_fecha_vencimiento);
							$fv = date_format($fv, 'd-m-Y');
							echo $fv;
						}
						?>
					</span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr style="margin: 0">
				</td>
			</tr>
			<tr>
				<td style="width: 50%" valign="top"><b>Constancia Inscripción Entregada: </b>
					<span>
						<?php if ($model->entrega_constancia_inscripcion == 1) { ?> Si <?php } else { ?> No<?php } ?>
					</span>
				</td>
				<td style="width: 50%" valign="top"><b>Responsable Entrega Constancia: </b><span><?= $model->entrega_constancia_inscripcion_nombre ?></span></td>
			</tr>
			<tr>
				<th><br></th>
			</tr>
		</table>

		<b>Observación: </b><?= $model->observaciones ?>

		<table>
			<tr>
				<td>
					<b>Posee documentación adjunta: </b><span><?= count($model->getAdjuntos()) > 0 ? 'Si' : 'No' ?></span>
				</td>
			</tr>
		</table>

		<table cellspacing="500">
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5> Detalle de Mandatos: </h5>
				</th>
			</tr>

			<?php
			if ($mandatos) { ?>
				<?php
				foreach ($mandatos as $mandato) { ?>
					<tr>
						<td style="width: 50%" style="width: 50%" valign="top"><b>Periodo:</b> Desde <span><?= $mandato['fecha_desde'] ?></span> - Hasta <span><?= $mandato['fecha_hasta'] ?></span></td>
						<td style="width: 50%" valign="top">
							<b>Carácter: </b><span>
								<?php if ($mandato['titular'] == 1) : ?>
									Titular
								<?php else : ?>
									Suplente
								<?php endif; ?>
							</span>
						</td>
					</tr>
					<tr>
						<td valign="top" colspan="2" style="text-align:justify"><b>Observación: </b><?= $mandato['observaciones'] ?></td>
					</tr>
					<tr>
						<td colspan="2">
							<hr style="margin: 5px 0 0 0; width: 70%">
						</td>
					</tr>
				<?php }
			} else { ?>
				<tr>
					<td>El registro no posee ningún mandato.</td>
				</tr>
			<?php } ?>
		</table>
	</div>
</body>

</html>