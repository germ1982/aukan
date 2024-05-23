<?php

use app\models\Sds_800_llamada;
use yii\helpers\Url;

$idllamada = $_GET['idllamada'];
$llamada_datos = $llamadaDatos;
$atencion_datos = $atencionDatos;
$agresores = $agresores;
$hijos = $hijos;
$arrayTipoViolencia = $arrayViolencias;
//arreglo con historial de llamadas-derivaciones
$movimientos = [];
$origen = $llamada_datos['idorigen'];
// Si la llamada tiene idorigen, se buscan los anteriores
while ($origen) {
	$query = new yii\db\Query;
	$query->select(["*"])
		->from(["sds_800_llamada llamada"])
		->where(["llamada.idllamada" => $origen]);
	$command = $query->createCommand();
	$origen_datos = $command->queryOne();
	array_unshift($movimientos, $origen_datos);
	$origen = $origen_datos['idorigen'];
}

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE ATENCIÓN</h4>
			<p><span><?= $llamada_datos['llamadaArea'] ?></span></p>
			<hr style="margin: 0">
		</div>

		<!-- Datos llamada -->

		<table>
			<tr>
				<td>
					<b>Nro. de Llamada: </b><span><?= $idllamada ?></span>
				</td>
				<td></td>
				<td>
					<b>Estado: </b><span><?= $llamada_datos['llamadaEstado'] ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Fecha de llamada: </b><span><?= $llamada_datos['fechaLlamada']; ?></span>
				</td>
				<td></td>
				<td>
					<b>Atendió: </b><span><?= $llamada_datos['usuarioapellido'] ?>, <?= $llamada_datos['usuarionombre'] ?></span>
				</td>
			</tr>
			<tr>
				<th><br></th>
			</tr>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5>DATOS DEL LLAMANTE: </h5>
				</th>
			</tr>
			<tr>
				<td>
					<b>Documento: </b><span><?= $llamada_datos['personadocumento']; ?></span>
				</td>
				<td></td>
				<td>
					<b>Nombre: </b> <span> <?= $llamada_datos['personaapellido']; ?>, <?= $llamada_datos['personanombre']; ?> </span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Fecha de Nacimiento: </b><span><?= $llamada_datos['personafechanacimiento']; ?></span>
				</td>
				<td></td>
				<td><b>Nacionalidad: </b><span><?= $llamada_datos['nacionalidad']; ?></span></td>
			</tr>
			<tr>
				<td><b>Género: </b><span><?= $llamada_datos['genero']; ?></span></td>
				<td></td>
				<td><b>Teléfono: </b><span><?= $llamada_datos['800telefono']; ?></span></td>
			</tr>
			<tr>
				<td><b>Domicilio: </b><span><?= $llamada_datos['800domicilio']; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td><b>Provincia: </b><span><?= $llamada_datos['800provincia']; ?></span></td>
				<td></td>
				<td><b>Localidad: </b><span><?= $llamada_datos['800localidad']; ?></span></td>
			</tr>
			<tr>
				<td><b>Institución: </b><span><?= $llamada_datos['llamadainstitucion']; ?></span></td>
				<td></td>
				<td><b>Vínculo: </b><span><?= $llamada_datos['llamadavinculo']; ?></span></td>
			</tr>
			<tr>
				<th><br></th>
			</tr>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5> Detalle de la Situación: </h5>
				</th>
			</tr>
		</table>

		<p class="parrafo" style="text-align:justify"><span><b>Descripción: </b><?= $llamada_datos['llamadadetalle']; ?></span></p>

		<table>
			<tr>
				<td><b>Afectado Dni: </b><span><?= $llamada_datos['llamadaafectadodni']; ?></span></td>
				<td></td>
				<td><b>Afectado Nombre: </b><span><?= $llamada_datos['llamadaafectadonombre']; ?></span></td>
			</tr>
			<tr>
				<td><b>Afectado Apodo: </b><span><?= $llamada_datos['llamadaafectadoapodo']; ?></span></td>
				<td></td>
				<td><b>Afectado Tratamiento: </b><span><?= $llamada_datos['llamadaTratamiento']; ?></span></td>
			</tr>
		</table>

		<!-- Datos intervencion -->

		<?php if ($atencion_datos) : ?>
			<table>
				<tr>
					<th><br></th>
				</tr>
				<tr style="background-color: #dddddd;">
					<th class="titulo">
						<h5> Detalle Atención: </h5>
					</th>
				</tr>
				<tr>
					<td><b>Nro. Intervención Violencia: </b><span><?= $atencion_datos['idintervencion']; ?></span></td>
					<td></td>
					<td><b>Usuario Carga: </b><span><?= $atencion_datos['nombre_atencion']; ?> <?= $atencion_datos['apellido_atencion'] ?></span></td>
				</tr>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>PERSONA AFECTADA </b></td>
				</tr>
				<tr>
					<td>
						<b>Documento: </b><span><?= $atencion_datos['doc_victima']; ?></span>
					</td>
					<td></td>
					<td>
						<b>Nombre: </b><span><?= $atencion_datos['nombre_victima']; ?>, <?= $atencion_datos['ape_victima']; ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<b>Fecha de Nacimiento: </b><span><?= $atencion_datos['nacimiento_victima']; ?></span>
					</td>
					<td></td>
					<td><b>Nacionalidad: </b><span><?= $atencion_datos['nac_victima']; ?></span></td>
				</tr>
				<tr>
					<td><b>Sexo: </b><span><?= $atencion_datos['sex_victima']; ?></span></td>
					<td></td>
					<td><b>Género Autopercibido: </b><span><?= $atencion_datos['genero_autopercibido']; ?></span></td>
				</tr>
				<tr>
					<td><b>Domicilio: </b><span><?= $atencion_datos['dom_victima']; ?></span></td>
				</tr>
				<tr>
					<td><b>Provincia: </b><span><?= $atencionDatos['prov_victima']; ?></span></td>
					<td></td>
					<td><b>Localidad: </b><span><?= $atencion_datos['loc_victima']; ?></span></td>
				</tr>
				<tr>
					<td><b>Teléfono: </b><span><?= $atencion_datos['tel_victima']; ?></span></td>
					<td></td>
					<td><b>Localidad Oriunda: </b><span><?= $atencion_datos['loc_oriunda']; ?></span></td>
				</tr>

			</table>

			<!-- Tipos de violencia -->

			<?php if ($atencion_datos['tipo_violencia_fisica'] || $atencion_datos['tipo_violencia_psicologica'] || $atencion_datos['tipo_violencia_sexual'] ||  $atencion_datos['tipo_violencia_economica_patrimonial']  || $atencion_datos['tipo_violencia_simbolica'] || $atencion_datos['tipo_violencia_negligencia_abandono'] || $atencion_datos['tipo_violencia_ambiental']) : ?>
				<table text-align: justify>
					<tr>
						<td colspan="3">
							<hr style="margin: 0">
						</td>
					</tr>
					<tr>
						<td><b>TIPOS DE VIOLENCIAS</b></td>
					</tr>

					<!-- VIOLENCIA FISICA -->
					<?php if ($atencion_datos['tipo_violencia_fisica']) { ?>
						<tr>
							<td valign="top" colspan="2">
								<b>Física</b>
							</td>
						</tr>
					<?php } ?>
					<?php if ($arrayTipoViolencia['violencia']['fisica']) { ?>
						<tr>
							<td valign="top" colspan="1">
								<b>Frecuencia:</b>
								<?= $arrayTipoViolencia['violencia']['fisicaFrecuencia'] ? $arrayTipoViolencia['violencia']['fisicaFrecuencia'] : '-' ?>
							</td>
							<td valign="top" colspan="1">
								<b>Ocurrencia:</b>
								<?= $arrayTipoViolencia['violencia']['fisicaOcurrencia'] ? $arrayTipoViolencia['violencia']['fisicaOcurrencia'] : '-' ?>
							</td>
							<td valign="top" colspan="1">
								<b>Vigencia:</b>
								<?= $arrayTipoViolencia['violencia']['fisicaVigencia'] == 1 ? 'Si' : ($arrayTipoViolencia['violencia']['fisicaVigencia'] == 0 ? 'No' : '-') ?>
							</td>
						</tr>
						<?php foreach ($arrayTipoViolencia['violencia']['fisica'] as $violencia) { ?>
							<tr>
								<td style="padding-left: 15px;" valign="top" colspan="3">
									- <?= ($violencia['descripcion']) ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>

					<!-- VIOLENCIA PSICOLOGICA -->
					<?php if ($atencion_datos['tipo_violencia_psicologica']) { ?>
						<tr>
							<td valign="top" colspan="2">
								<b>Psicológica</b>
							</td>
						</tr>
					<?php } ?>
					<?php if ($arrayTipoViolencia['violencia']['psicologica']) { ?>
						<tr>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Frecuencia:</b>
								<?= $arrayTipoViolencia['violencia']['psicologicaFrecuencia'] ? $arrayTipoViolencia['violencia']['psicologicaFrecuencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Ocurrencia:</b>
								<?= $arrayTipoViolencia['violencia']['psicologicaOcurrencia'] ? $arrayTipoViolencia['violencia']['psicologicaOcurrencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Vigencia:</b>
								<?= $arrayTipoViolencia['violencia']['psicologicaVigencia'] == 1 ? 'Si' : ($arrayTipoViolencia['violencia']['psicologicaVigencia'] == 0 ? 'No' : '-') ?>
							</td>
						</tr>
						<?php foreach ($arrayTipoViolencia['violencia']['psicologica'] as $violenciaPsicologica) { ?>
							<tr>
								<td style="padding-left: 15px;" valign="top">
									- <?= $violenciaPsicologica['descripcion'] ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>

					<!-- VIOLENCIA SEXUAL -->
					<?php if ($atencion_datos['tipo_violencia_sexual']) { ?>
						<tr>
							<td valign="top" colspan="2">
								<b>Sexual</b>
							</td>
						</tr>
					<?php } ?>
					<?php if ($arrayTipoViolencia['violencia']['sexual']) { ?>
						<tr>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Frecuencia:</b>
								<?= $arrayTipoViolencia['violencia']['sexualFrecuencia'] ? $arrayTipoViolencia['violencia']['sexualFrecuencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Ocurrencia:</b>
								<?= $arrayTipoViolencia['violencia']['sexualOcurrencia'] ? $arrayTipoViolencia['violencia']['sexualOcurrencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Vigencia:</b>
								<?= $arrayTipoViolencia['violencia']['sexualVigencia'] == 1 ? 'Si' : ($arrayTipoViolencia['violencia']['sexualVigencia'] == 0 ? 'No' : '-') ?>
							</td>
						</tr>
						<?php foreach ($arrayTipoViolencia['violencia']['sexual'] as $violenciaSexual) { ?>
							<tr>
								<td style="padding-left: 15px;" valign="top">
									- <?= $violenciaSexual['descripcion'] ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>

					<!-- VIOLENCIA ECONOMICA PATRIMONIAL -->
					<?php if ($atencion_datos['tipo_violencia_economica_patrimonial']) { ?>
						<tr>
							<td valign="top" colspan="2">
								<b>Económica patrimonial</b>
							</td>
						</tr>
					<?php } ?>
					<?php if ($arrayTipoViolencia['violencia']['economicaPatrimonial']) { ?>
						<tr>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Frecuencia:</b>
								<?= $arrayTipoViolencia['violencia']['economicaPatrimonialFrecuencia'] ? $arrayTipoViolencia['violencia']['economicaPatrimonialFrecuencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Ocurrencia:</b>
								<?= $arrayTipoViolencia['violencia']['economicaPatrimonialOcurrencia'] ? $arrayTipoViolencia['violencia']['economicaPatrimonialOcurrencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Vigencia:</b>
								<?= $arrayTipoViolencia['violencia']['economicaPatrimonialVigencia'] == 1 ? 'Si' : ($arrayTipoViolencia['violencia']['economicaPatrimonialVigencia'] == 0 ? 'No' : '-') ?>
							</td>
						</tr>
						<?php foreach ($arrayTipoViolencia['violencia']['economicaPatrimonial'] as $economicaPatrimonial) { ?>
							<tr>
								<td style="padding-left: 15px;" valign="top">
									- <?= $economicaPatrimonial['descripcion'] ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>

					<!-- VIOLENCIA VIOLENCIA SIMBOLICA -->
					<?php if ($atencion_datos['tipo_violencia_simbolica']) { ?>
						<tr>
							<td valign="top" colspan="2">
								<b>Simbólica</b>
							</td>
						</tr>
					<?php } ?>
					<?php if ($arrayTipoViolencia['violencia']['simbolica']) { ?>
						<tr>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Frecuencia:</b>
								<?= $arrayTipoViolencia['violencia']['simbolicaFrecuencia'] ? $arrayTipoViolencia['violencia']['simbolicaFrecuencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Ocurrencia:</b>
								<?= $arrayTipoViolencia['violencia']['simbolicaOcurrencia'] ? $arrayTipoViolencia['violencia']['simbolicaOcurrencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Vigencia:</b>
								<?= $arrayTipoViolencia['violencia']['simbolicaVigencia'] == 1 ? 'Si' : ($arrayTipoViolencia['violencia']['simbolicaVigencia'] == 0 ? 'No' : '-') ?>
							</td>
						</tr>
						<?php foreach ($arrayTipoViolencia['violencia']['simbolica'] as $simbolica) { ?>
							<tr>
								<td style="padding-left: 15px;" valign="top">
									- <?= $simbolica['descripcion'] ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>

					<!-- VIOLENCIA NEGLIGENCIA ABANDONO -->
					<?php if ($atencion_datos['tipo_violencia_negligencia_abandono']) { ?>
						<tr>
							<td valign="top" colspan="2">
								<b>Negligencia - abandono</b>
							</td>
						</tr>
					<?php } ?>
					<?php if ($arrayTipoViolencia['violencia']['negligenciaAbandono']) { ?>
						<tr>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Frecuencia:</b>
								<?= $arrayTipoViolencia['violencia']['negligenciaAbandonoFrecuencia'] ? $arrayTipoViolencia['violencia']['negligenciaAbandonoFrecuencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Ocurrencia:</b>
								<?= $arrayTipoViolencia['violencia']['negligenciaAbandonoOcurrencia'] ? $arrayTipoViolencia['violencia']['negligenciaAbandonoOcurrencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Vigencia:</b>
								<?= $arrayTipoViolencia['violencia']['negligenciaAbandonoVigencia'] == 1 ? 'Si' : ($arrayTipoViolencia['violencia']['negligenciaAbandonoVigencia'] == 0 ? 'No' : '-') ?>
							</td>
						</tr>
						<?php foreach ($arrayTipoViolencia['violencia']['negligenciaAbandono'] as $negligenciaAbandono) { ?>
							<tr>
								<td style="padding-left: 15px;" valign="top">
									- <?= $negligenciaAbandono['descripcion'] ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>

					<!-- VIOLENCIA VIOLENCIA AMBIENTAL -->
					<?php if ($atencion_datos['tipo_violencia_ambiental']) { ?>
						<tr>
							<td valign="top" colspan="2">
								<b>Ambiental</b>
							</td>
						</tr>
					<?php } ?>
					<?php if ($arrayTipoViolencia['violencia']['ambiental']) { ?>
						<tr>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Frecuencia:</b>
								<?= $arrayTipoViolencia['violencia']['ambientalFrecuencia'] ? $arrayTipoViolencia['violencia']['ambientalFrecuencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Ocurrencia:</b>
								<?= $arrayTipoViolencia['violencia']['ambientalOcurrencia'] ? $arrayTipoViolencia['violencia']['ambientalOcurrencia'] : '-' ?>
							</td>
							<td style="padding-left: 15px;" valign="top" colspan="1">
								<b>Vigencia:</b>
								<?= $arrayTipoViolencia['violencia']['ambientalVigencia'] == 1 ? 'Si' : ($arrayTipoViolencia['violencia']['ambientalVigencia'] == 0 ? 'No' : '-') ?>
							</td>
						</tr>
						<?php foreach ($arrayTipoViolencia['violencia']['ambiental'] as $ambiental) { ?>
							<tr>
								<td style="padding-left: 15px;" valign="top">
									- <?= $ambiental['descripcion'] ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</table>
			<?php endif; ?>

			<!-- Hijos -->
			<table>
				<?php if ($hijos != []) : ?>
					<tr>
						<td colspan="3">
							<hr style="margin: 0">
						</td>
					</tr>
					<tr>
						<td colspan="3"><b>HIJOS</b></td>
					</tr>

					<?php foreach ($hijos as $hijo) : ?>
						<tr>
							<td><b>Nombre: </b><span><?= $hijo['documento']; ?> - <?= $hijo['nombre']; ?> <?= $hijo['apellido'] ?></td>
							<?php if ($hijo['conviviente'] == 1) : ?>
								<td>Conviviente </td>
							<?php else : ?>
								<td> No Conviviente </td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>

			<!-- Agresores -->

			<table>
				<?php if ($agresores != []) : ?>
					<tr>
						<td colspan="3">
							<hr style="margin: 0">
						</td>
					</tr>
					<tr>
						<td colspan="3"><b>PERSONA AGRESORA</b></td>
					</tr>
					<div class="row">
						<?php foreach ($agresores as $agresor) : ?>
							<tr>
								<td style="padding-left: 15px;"><b>Agresor: </b><span><?= $agresor['dni'] ? $agresor['dni'] : ' '; ?> - <?= $agresor['nombre']; ?> <?= $agresor['apellido'] ?></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;">
									<b>Género: </b><span><?= $agresor['generoDetalle']; ?></span>
								</td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;">
									<b>Parentesco: </b><span><?= $agresor['parentesco']; ?></span>
								</td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b>Dato Denuncia: </b><span><?= $agresor['agresor_dato_denuncia']; ?></span></td>
								<td></td>
							</tr>
							<tr>
								<?php if ($agresor['agresor_dav'] == 1) : ?>
									<td style="padding-left: 15px;"><b>D.A.V. : </b><span>
											<?= $agresor['agresor_dav_datos']; ?></span></td>
									<td></td>
								<?php endif; ?>
							</tr>
							<tr>
								<?php if ($agresor['agresor_problematico'] == 1) : ?>
									<td style="padding-left: 15px;"><b> Agresor problemático: </b><span>
											<?= $agresor['agresor_consumo']; ?></span></td>
									<td></td>
								<?php endif; ?>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Escolaridad alcanzada: </b><span>
										<?= $agresor['escolaridadDetalle'] ? $agresor['escolaridadDetalle'] : ''  ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Es o fue funcionario/a público: </b><span>
										<?= $agresor['funcionario'] != null ? ($agresor['funcionario'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Realiza alguna actividad por la que le descuentan dinero: </b><span>
										<?= $agresor['desc_actividad'] != null ? ($agresor['desc_actividad'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Por esa actividad le descuentan para la jubilación: </b><span>
										<?= $agresor['desc_jubilacion'] != null ? ($agresor['desc_jubilacion'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Acceso a armas de fuego: </b><span>
										<?= $agresor['acceso_armas'] != null ? ($agresor['acceso_armas'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Antecedentes penales: </b><span>
										<?= $agresor['antecedente_penales'] != null ? ($agresor['antecedente_penales'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Antecedentes de violencia con parejas o ex parejas: </b><span>
										<?= $agresor['antecedente_violencia'] != null ? ($agresor['antecedente_violencia'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Antecendentes de violación de medidas de restrición: </b><span>
										<?= $agresor['antecedente_restricciones'] != null ? ($agresor['antecedente_restricciones'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Vínculo con actividades ilícitas: </b><span>
										<?= $agresor['vinculo_ilicito'] != null ? ($agresor['vinculo_ilicito'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Vínculo con personal de seguridad: </b><span>
										<?= $agresor['vinculoPersonalSeguridad'] ? $agresor['vinculoPersonalSeguridad'] : '-'; ?></span></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;"><b> Consumo problemático: </b><span>
										<?= $agresor['consumo_problematico'] != null ? ($agresor['consumo_problematico'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
								<td></td>
							</tr>
							<tr>
								<?php if ($agresor['consumo_problematico'] == 1) : ?>
									<td style="padding-left: 15px;"><b> Tipos de consumo problemático: </b><span>
											<?php foreach ($agresor['consumoDetalle'] as $detalleConsumoAgresor) : ?>
												<?php echo "{$detalleConsumoAgresor['consumoDetalle']} -" ?>
											<?php endforeach; ?>
									<td></td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</table>

			<!-- Movimientos -->

			<table>
				<?php if ($arrayMovimientos != []) : ?>
					<tr>
						<td colspan="3">
							<hr style="margin: 0">
						</td>
					</tr>
					<tr>
						<td colspan="3"><b>MOVIMIENTOS</b></td>
					</tr>
					<?php foreach ($arrayMovimientos as $clave => $mov) : ?>
						<tr>
							<td style="padding-left: 15px;" colspan="1"><b>Tipo de Movimiento: </b><?= $mov['tipo_movimiento']; ?></td>
							<td style="padding-left: 15px;" colspan="1"><b>Fecha: </b><?= $mov['fecha']; ?></td>
						</tr>
						<tr>
							<td style="padding-left: 15px;" colspan="3">
								<b>Profesionales: </b><?= $mov['profesionales_intervinientes']; ?>
							</td>
						</tr>
						<tr>
							<td style="padding-left: 15px;" colspan="3">
								<b>Detalle: </b><?= $mov['detalle']; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>


			<table>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>REFERENTE TERCIARIO</b></td>
				</tr>
				<tr>
					<td style="padding-left: 15px;"><b>Nombre y Apellido: </b><span><?= $atencion_datos['referente_nombre']; ?></span></td>
					<td></td>
					<td style="padding-left: 15px;"><b>Teléfono: </b><span><?= $atencion_datos['referente_telefono']; ?></span></td>
					<td></td>
				</tr>
				<tr>
					<td style="padding-left: 15px;"><b>Vínculo: </b><span><?= $atencion_datos['referente_vinculo']; ?></span></td>
				</tr>
			</table>

			<table>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>ABORDAJE</b></td>
				</tr>
				<tr>
					<td><b>Fecha de Abordaje: </b><span><?= $atencion_datos['fechaatencion']; ?></span></td>
					<td></td>
					<td><b>Nuevo Ingreso: </b><span><?= $atencion_datos['ingreso']; ?></span></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Tipo de Situación: </b><span><?= $atencion_datos['tipo_situacion']; ?></span></td>
					<td></td>
					<td><b>Tipo de Intervención: </b><span><?= $atencion_datos['tipo']; ?></span></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Derivación: </b><span><?= $atencion_datos['derivacion']; ?></span></td>
					<td></td>
					<td><b>Denuncia: </b><span><?= $atencion_datos['denuncia']; ?></span></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Juzgado: </b><span><?= $atencion_datos['juzgado']; ?></span></td>
					<td></td>
					<td><b>Localidad de Hecho: </b><span><?= $atencion_datos['loc_hecho']; ?></span></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3"><b>Consumo Problemático: </b><span><?= $atencion_datos['consoumo_problemático']; ?></span></td>
				</tr>
			</table>

			<p class="parrafo" style="text-align:justify"><b>Detalle: </b><span><?= $atencion_datos['detalle']; ?></span></p>
			<p class="parrafo" style="text-align:justify"><b>Profesionales intervinientes: </b><span><?= $atencion_datos['profesionales_intervinientes']; ?></span></p>
			<table>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>ABORDAJES COMPLEMENTARIOS</b></td>
				</tr>
			</table>
			<p class="parrafo" style="text-align:justify"><b>Abordaje: </b><span><?= $atencion_datos['abordaje_complementario']; ?></span></p>
		<?php endif; ?>

		<?php if ($llamada_datos['tipo'] || $llamada_datos['idderivacion']) : ?>
			<table>
				<tr>
					<th><br></th>
				</tr>
				<tr style="background-color: #dddddd;">
					<th class="titulo">
						<h5> Detalle de la Derivación: </h5>
					</th>
				</tr>
			</table>
			<table>
				<tr>
					<td><b>Tipo: </b><span><?= $llamada_datos['tipo']; ?></span></td>
					<td></td>
					<td><b>Derivacion: </b><span><?= $llamada_datos['derivaciondetalle']; ?></span></td>
				</tr>
				<tr>
					<td><b>Dirección: </b><span><?= $llamada_datos['derivaciondireccion']; ?></span></td>
					<td></td>
					<td><b>Teléfonos: </b><span><?= $llamada_datos['derivaciontelefonos']; ?></span></td>
				</tr>
				<tr>
					<td><b>Referente: </b><span><?= $llamada_datos['llamadaderivacionreferente']; ?></span></td>
					<td></td>
					<td><b>Fecha Derivación: </b><span><?= $llamada_datos['fechaDerivacion']; ?></span></td>
				</tr>
				<tr>
					<td>
						<b>Derivado por: </b><span><?= $llamada_datos['usuario_deriva_nombre'] ?> <?= $llamada_datos['usuario_deriva_apellido'] ? $llamada_datos['usuario_deriva_apellido'] : '-' ?></span>
					</td>
				</tr>
			</table>
			<p class="parrafo" style="text-align:justify"><b>Descripción: </b><?= $llamada_datos['llamadaderivaciondetalle']; ?></p>
		<?php endif; ?>
		<table>
			<tr>
				<th><br></th>
			</tr>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5> Detalle de la Dirección: </h5>
				</th>
			</tr>
			<tr>
				<td colspan="3"><b>Dirección: </b><span><?= $llamada_datos['llamadadireccion']; ?> - <a target="_blank" href="https://www.google.com.ar/maps/search/?api=1&query=<?= $llamada_datos['llamadalatitud']; ?>,<?= $llamada_datos['llamadalongitud']; ?>">Ver Mapa</a></span></td>
			</tr>
			<tr>
				<td><b>Longitud: </b><span><?= $llamada_datos['llamadalongitud']; ?></span></td>
				<td></td>
				<td><b>Latitud: </b><span><?= $llamada_datos['llamadalatitud']; ?></span></td>
			</tr>
			<?php if ($llamada_datos['llamadaEstado'] == 'Cerrada' || $llamada_datos['llamadaEstado'] == 'Situación Despejada') : ?>
				<tr>
					<th><br></th>
				</tr>
				<tr style="background-color: #dddddd;">
					<th class="titulo">
						<h5> Detalle Cierre / Despeje: </h5>
					</th>
				</tr>
				<tr>
					<td colspan="3">
						<b>Fecha: </b><span><?= $llamada_datos['fechaCierre'] ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<b>Detalle: </b><span><?= $llamada_datos['llamadacierredetalle']; ?></span>
					</td>
				</tr>
			<?php endif; ?>
		</table>
		<?php if (count($movimientos)) : ?>
			<table>
				<tr>
					<th><br></th>
				</tr>
				<tr style="background-color: #dddddd;">
					<th class="titulo">
						<h5> Movimientos Anteriores: </h5>
					</th>
				</tr>
				<tr>
					<td><b>Nro Llamada</b></td>
					<td><b>Area</b></td>
					<td><b>Fecha</b></td>
					<td><b>Link</b></td>
				</tr>
				<?php foreach ($movimientos as $movimiento) : ?>
					<tr>
						<td><?= $movimiento['idllamada'] ?></td>
						<td><?php switch ($movimiento['area']) {
								case Sds_800_llamada::AREA_SITUACIONDECALLE:
									echo 'Situación de Calle';
									break;
								case Sds_800_llamada::AREA_FAMILIA:
									echo 'Familia';
									break;
								case Sds_800_llamada::AREA_ADULTOSMAYORES:
									echo 'Adultos Mayores';
									break;
								case Sds_800_llamada::AREA_INTERIOR:
									echo 'Interior';
									break;
								case Sds_800_llamada::AREA_VIOLENCIA:
									echo 'Violencia';
									break;
							} ?></td>
						<td><?= date_format(date_create($movimiento['fecha_hora']), 'd-m-Y'); ?></td>
						<td>
							<a target="_blank" href="<?= Url::to(['/sds_800_llamada/reporte_llamada', 'idllamada' => $movimiento['idllamada'], 'area' => $movimiento['area']]); ?>">Ver Llamada Anterior</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
</body>