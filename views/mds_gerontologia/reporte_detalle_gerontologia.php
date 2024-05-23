<html>
<?php
function calculaedad($fechanacimiento)
{
	$edad = "";
	if ($fechanacimiento) {
		$data_birth = new DateTime($fechanacimiento); //Crea el objeto DateTime a partir de un string de fecha
		$data_hoy = new DateTime(); //devuelve la fecha actual
		$edad = $data_birth->diff($data_hoy); //Aplicamos la diferencia entre fechas
		$edad = $edad->y;
	}
	return $edad;
}
?>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE GERONTOLOGÍA</h4>
			<p><span> </span></p>
			<hr style="margin: 0 0 20px 0">
		</div>
		<table style="text-align: justify;">
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5><?= "{$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})" ?> </h5>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 50%">
					<b>Fecha de Atención: </b>
					<span>
						<?php
						$fv = date_create($model->fecha_atencion);
						$fv = date_format($fv, 'd-m-Y');
						echo $fv
						?>
					</span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="2">
					<b>Obra social: </b><span><?= $model->obrasocial ? $model->obrasocial->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Domicilio: </b><span><?= $model->domicilio ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Teléfono: </b><span><?= $model->telefono ? $model->telefono : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" style="width: 50%">
					<b>Estado civil: </b><span><?= $model->estadocivil ? $model->estadocivil->descripcion : '' ?></span>
				</td>
				<td valign="top" style="width: 50%">
					<b>Vivienda: </b><span><?= $model->vivienda ? $model->vivienda->descripcion : '' ?> <?= $model->residencia ? $model->residencia : '' ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<b>Biografía</b>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 50%;">
					<b>Lugar de Nacimiento: </b><span><?= $model->lugar_nacimiento ?></span>
				</td>
				<td valign="top" style="width: 50%;">
					<b>Escolaridad: </b><span><?= $model->escolaridad ? $model->escolaridad->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>

				<td valign="top" colspan="2">
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

			<tr>
				<td valign="top" colspan="2">
					<b>Algunas vivencias que marcaron su vida: </b><span><?= $model->vivencias ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="2">
					<b>Tiempo libre: </b><span><?= $model->tiempo_libre ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<h5>Hábitos</h5>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 30%;">
					<b>Fuma: </b><span><?= ($model->fuma == '') ? '' : ($model->fuma == 1 ? 'Si' : 'No') ?></span>
				</td>
				<td valign="top" style="width: 30%;">
					<b>Sueño adecuado: </b><span><?= ($model->suenio_adecuado == '') ? '' : ($model->suenio_adecuado == 1 ? 'Si' : 'No') ?></span>
				</td>
				<td valign="top" style="width: 30%">
					<b>Ejercicio físico cotidiano: </b><span><?= ($model->ejercicio_fisico == '') ? '' : ($model->ejercicio_fisico == 1 ? 'Si' : 'No') ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<h5>Vacunas</h5>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 50%;">
					<b>Obligatorias: </b><span><?= $model->vacunas_obligatorias == 1 ? 'Si' : 'No' ?></span>
				</td>
				<td valign="top" style="width: 50%;">
					<b>Vacunas COVID-19: </b><span><?= $model->vacunascovid19 ? $model->vacunascovid19->descripcion : '' ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<h5>Continencia esfínteres</h5>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 50%">
					<b>Diuresis: </b><span><?= ($model->diuresis == '') ? '' : ($model->diuresis == 1 ? 'Si' : 'No') ?></span>
				</td>
				<td valign="top" style="width: 50%">
					<b>Catarsis: </b><span><?= ($model->catarsis == '') ? '' : ($model->catarsis == 1 ? 'Si' : 'No') ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<h5>Antecedentes personales patológicos relevantes</h5>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 50%">
					<b>HTA: </b><span><?= ($model->antecedentes_hta == '') ? '' : ($model->antecedentes_hta == 1 ? 'Si' : 'No') ?></span>
				</td>
				<td valign="top" style="width: 50%">
					<b>ACV: </b><span><?= ($model->antecedentes_acv == '') ? '' : ($model->antecedentes_acv == 1 ? 'Si' : 'No') ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" style="width: 50%">
					<b>Enfermedades cardiovasculares (IAM, Trombosis, etc): </b><span><?= ($model->antecedentes_cardiaca == '') ? '' : ($model->antecedentes_cardiaca == 1 ? 'Si' : 'No') ?></span>
				</td>
				<td valign="top" style="width: 50%">
					<b>Diabetes: </b><span><?= ($model->antecedentes_diabetes == '') ? '' : ($model->antecedentes_diabetes == 1 ? 'Si' : 'No') ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Cáncer: </b><span><?= ($model->antecedentes_cancer == '') ? '' : ($model->antecedentes_cancer == 1 ? 'Si' : 'No') ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="2">
					<b>Otras: </b><span><?= $model->antecedentes_otras ? $model->antecedentes_otras : '' ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<td valign="top">
					<b>Caídas en los últimos 6 meses: </b><span><?= $model->caidas == 1 ? 'Si' : 'No' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Medicación actual: </b><span><?= $model->medicacion_actual ? $model->medicacion_actual : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Laboratorios y estudios complementarios realizados el último año: </b><span><?= $model->estudios_complementarios ? $model->estudios_complementarios : '' ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<h5>Examen físico</h5>
				</th>
			</tr>
			<tr>
				<td valign="top" style="width: 33%">
					<b>TA: </b><span><?= $model->examen_fis_ta ?></span>
				</td>
				<td valign="top" style="width: 33%">
					<b>Sat O2: </b><span><?= $model->examen_fis_sato2 ?></span>
				</td>
				<td valign="top" style="width: 33%">
					<b>FC lat/minuto: </b><span><?= $model->examen_fis_fc ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="3">
					<b>Abdomen: </b><span><?= $model->examen_fis_abdomen ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="3">
					<b>Aparato respiratorio: </b><span><?= $model->examen_fis_aparato_respiratorio ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="3">
					<b>Miembros inferiores: </b><span><?= $model->examen_fis_miembros_inferiores ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="3">
					<b>Observaciones: </b><span><?= $model->examen_fis_observaciones ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<b>Evaluación funcional ABVD: </b><?= $model_evaluacion->abvd ?><br>
				</th>
			</tr>
			<tr>
				<td valign="top">
					<b>Lavado: </b><span><?= $model_evaluacion->abvdlavado ? $model_evaluacion->abvdlavado->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Vestido: </b><span><?= $model_evaluacion->abvdvestido ? $model_evaluacion->abvdvestido->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Uso del baño: </b><span><?= $model_evaluacion->abvdbanio ? $model_evaluacion->abvdbanio->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Movilización: </b><span><?= $model_evaluacion->abvdmovilizacion ? $model_evaluacion->abvdmovilizacion->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Continencia: </b><span><?= $model_evaluacion->abvdcontinencia ? $model_evaluacion->abvdcontinencia->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Alimentación: </b><span><?= $model_evaluacion->abvdalimentacion ? $model_evaluacion->abvdalimentacion->descripcion : '' ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<b>Evaluación funcional AIVD: </b> <?= $model_evaluacion->aivd ? $model_evaluacion->aivd : '' ?><br>
				</th>
			</tr>
			<tr>
				<td valign="top">
					<b>Capacidad para usar el teléfono: </b><span><?= $model_evaluacion->aivdcapacidadtelefono ? $model_evaluacion->aivdcapacidadtelefono->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Preparación de la comida: </b><span><?= $model_evaluacion->aivdpreparacioncomida ? $model_evaluacion->aivdpreparacioncomida->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Cuidado de la casa: </b><span><?= $model_evaluacion->aivdcuidadocasa ? $model_evaluacion->aivdcuidadocasa->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Lavado de ropa: </b><span><?= $model_evaluacion->abvdmovilizacion ? $model_evaluacion->abvdmovilizacion->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Uso de medios de transporte: </b><span><?= $model_evaluacion->aivdusotransporte ? $model_evaluacion->aivdusotransporte->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Responsabilidad respecto a su medicación: </b><span><?= $model_evaluacion->aivdresponsabilidadmedicacion ? $model_evaluacion->aivdresponsabilidadmedicacion->descripcion : '' ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<th>
					<b>Evaluación Social: </b> <?= $model_evaluacion->ev_social_total ?><br>
					<span>(Escala de valoración socio familiar de Gijón)</span>
				</th>
			</tr>
			<tr>
				<td valign="top">
					<b>a) Situación familiar: </b><span><?= $model_evaluacion->situacionfamiliar->descripcion ? $model_evaluacion->situacionfamiliar->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>b) Relaciones sociales: </b><span><?= $model_evaluacion->relacionessociales->descripcion ? $model_evaluacion->relacionessociales->descripcion : '' ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>c) Apoyos de la red social: </b><span><?= $model_evaluacion->redsocial->descripcion ? $model_evaluacion->redsocial->descripcion : '' ?></span>
				</td>
			</tr>
		</table>
		<hr>
		<table text-align: justify;>
			<tr>
				<td valign="top">
					<b>Problemas actuales que impactan en su calidad de vida: </b><span><?= $model->problemas_actuales ?></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<b>Recomendaciones: </b><span><?= $model->recomendaciones ?></span>
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
		<hr>
		<!------------------------------------------------  ICOPE  --------------------------------------------------------------->
		<div class="row">
			<div class="col-md-12">
				<h5 style="margin: 0"><b>INSTRUMENTO ICOPE DE DETECCIÓN DE LA OMS</b></h5>
			</div>
		</div>
		<hr style="margin: 25px 0 0 0">
	</div>
</body>

</html>