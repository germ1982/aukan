<?php

$idintervencion = $_GET['idintervencion'];

$query = new yii\db\Query;
$query->select(["comper.idpersona, intervencion.idintervencion, comper.documento AS doc_victima, UPPER(comper.nombre) AS nombre_victima, UPPER(comper.apellido) AS ape_victima,
	(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=comper.genero)  AS sex_victima,
	(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=vioper.genero_autopercibido)  AS genero_autopercibido, 
	(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=comper.nacionalidad) AS nac_victima,
	DATE_FORMAT(comper.fecha_nacimiento,'%d/%m/%Y') as nacimiento_victima,
	  vioper.telefono AS tel_victima, vioper.domicilio AS dom_victima, 
	(SELECT descripcion FROM sds_com_localidad WHERE idlocalidad= vioper.idlocalidad) AS loc_victima,
	(SELECT descripcion FROM sds_com_localidad WHERE idlocalidad= vioper.localidad_oriunda) AS loc_oriunda,
		DATE_FORMAT(intervencion.fecha,'%d/%m/%Y') as fechaatencion,
	UPPER(usuario.nombre) as nombre_atencion, UPPER(usuario.apellido) as apellido_atencion,
	case  intervencion.ingreso
	WHEN 0 then 'Re-ingreso'
	WHEN 1 then 'Nuevo Ingreso'
	END as ingreso, (SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=intervencion.tipo) AS tipo,
	(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=intervencion.derivacion) AS derivacion,
	(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=intervencion.tipo_modalidad) AS modalidad,
	case  intervencion.denuncia
	WHEN 0 then 'No realizó denuncia'
	WHEN 1 then 'Si realizó denuncia'
	END as denuncia, intervencion.juzgado, intervencion.detalle, intervencion.detalle_plataforma,intervencion.profesionales_intervinientes,
	intervencion.tipo_violencia_fisica,intervencion.tipo_violencia_psicologica,intervencion.tipo_violencia_sexual,
	intervencion.tipo_violencia_economica_patrimonial,tipo_violencia_simbolica,intervencion.tipo_violencia_negligencia_abandono,tipo_violencia_ambiental,referente_nombre,
	referente_telefono, referente_vinculo, 
	case  intervencion.tipo_situacion
	WHEN 0 then 'Código A'
	WHEN 1 then 'Código B'
	when 2 then 'Asesoramiento'
	END as tipo_situacion, 
	abordaje_complementario,
	(SELECT descripcion FROM sds_com_localidad WHERE idlocalidad= intervencion.localidad_hecho) AS loc_hecho,
	intervencion.idllamada, 
		(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=intervencion.tipo_violencia) AS tipo_violencia,
	case  intervencion.consumo_problematico
	WHEN 1 then 'Presenta consumo problemático'
	WHEN 0 then 'No presenta consumo problemático'
	END as consoumo_problemático"])
	->from(["sds_vio_intervencion intervencion"])
	->join("join", "sds_vio_persona as vioper", "vioper.idpersona=intervencion.idpersona")
	->join("join", "sds_com_persona as comper", "comper.idpersona = vioper.idpersona")
	->join("join", "mds_seg_usuario as usuario", "usuario.idusuario= intervencion.idusuario ")
	->where(["intervencion.idintervencion" => $idintervencion]);

$command = $query->createCommand();
$atencion_datos = $command->queryOne();

//arreglo de hijos
$hijos = [];
$query = new yii\db\Query;
$query->select(["idpersona", "documento", "nombre", "apellido", "conviviente"])
	->from(["sds_com_persona"])
	->where(["padre" => $atencion_datos['idpersona']]);
$command = $query->createCommand();
$hijos = $command->queryAll();

?>
<html>

<body>
	<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
	<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
		<h4 style="margin: 0; font-weight: bold;">REPORTE INTERVENCIÓN VIOLENCIA</h4>
		<hr style="margin: 0">
	</div>
	<table>
		<tr style="background-color: #dddddd;">
			<th class="titulo">
				<h5> Detalle Atencion</h5>
			</th>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Nro. Intervención Violencia: </b><span><?= $atencion_datos['idintervencion']; ?></span></td>
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
			<td style="padding-left: 15px;">
				<b>Documento: </b><span><?= $atencion_datos['doc_victima']; ?></span>
			</td>
			<td></td>
			<td>
				<b>Nombre: </b><span> <?= $atencion_datos['ape_victima']; ?>, <?= $atencion_datos['nombre_victima']; ?></span>
			</td>
		</tr>
		<tr>
			<td style="padding-left: 15px;">
				<b>Fecha de Nacimiento: </b><span><?= $atencion_datos['nacimiento_victima']; ?></span>
			</td>
			<td></td>
			<td><b>Nacionalidad: </b><span><?= $atencion_datos['nac_victima']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Sexo: </b><span><?= $atencion_datos['sex_victima']; ?></span></td>
			<td></td>
			<td><b>Género Autopercibido: </b><span><?= $atencion_datos['genero_autopercibido']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Domicilio: </b><span><?= $atencion_datos['dom_victima']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Provincia: </b><span><?= $arrayPerAfectada['provincia']; ?></span></td>
			<td></td>
			<td><b>Localidad: </b><span><?= $atencion_datos['loc_victima']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Provincia Oriunda: </b><span><?= $arrayPerAfectada['provincia_oriunda']; ?></span></td>
			<td></td>
			<td><b>Localidad Oriunda: </b><span><?= $atencion_datos['loc_oriunda']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Teléfono: </b><span><?= $atencion_datos['tel_victima']; ?></span></td>
		</tr>
	</table>

    <!-- Referente tercero y abordaje -->
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
			<td><b>Teléfono: </b><span><?= $atencion_datos['referente_telefono']; ?></span></td>
		</tr>
		<tr>
			<td colspan="3" style="padding-left: 15px;"><b>Vínculo: </b><span><?= $atencion_datos['referente_vinculo']; ?></span></td>
		</tr>
		<tr>
			<td colspan="3">
				<hr style="margin: 0">
			</td>
		</tr>
		<tr>
			<td colspan="3"><b>ABORDAJE</b></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Fecha de Abordaje: </b><span><?= $atencion_datos['fechaatencion']; ?></span></td>
			<td></td>
			<td><b>Nuevo Ingreso: </b><span><?= $atencion_datos['ingreso']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Tipo de Situación: </b><span><?= $atencion_datos['tipo_situacion']; ?></span></td>
			<td></td>
			<td><b>Tipo de Intervención: </b><span><?= $atencion_datos['tipo']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Derivación: </b><span><?= $atencion_datos['derivacion']; ?></span></td>
			<td></td>
			<td><b>Denuncia: </b><span><?= $atencion_datos['denuncia']; ?></span></td>
		</tr>
		<tr>
			<td colspan="3" style="padding-left: 15px;"><b>Juzgado: </b><span><?= $atencion_datos['juzgado']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Provincia del Hecho: </b><span><?= $arrayAbordaje['provincia_hecho']; ?></span></td>
			<td></td>
			<td><b>Localidad del Hecho: </b><span><?= $atencion_datos['loc_hecho']; ?></span></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;"><b>Consumo Problemático: </b><span><?= $atencion_datos['consoumo_problemático']; ?></span></td>
			<td></td>
			<td><b>Tipo modalidad: </b><span><?= $atencion_datos['modalidad'] ?? ''; ?></span></td>
		</tr>
	</table>
	<div style="text-align:justify; padding-left: 8px;">
		<p class="parrafo"><b>Detalle: </b><span><?= $atencion_datos['detalle']; ?></span></p>
		<p class="parrafo"><b>Detalle para plataforma vulnerabilidad: </b><span><?= $atencion_datos['detalle_plataforma']; ?></span></p>
		<p class="parrafo"><b>Profesionales intervinientes: </b><span><?= $atencion_datos['profesionales_intervinientes']; ?></span></p>
	</div>

    <!-- Tipos de violencia -->

	<?php if ($atencion_datos['tipo_violencia_fisica'] || $atencion_datos['tipo_violencia_psicologica'] || $atencion_datos['tipo_violencia_sexual'] ||  $atencion_datos['tipo_violencia_economica_patrimonial']  || $atencion_datos['tipo_violencia_simbolica'] || $atencion_datos['tipo_violencia_negligencia_abandono'] || $atencion_datos['tipo_violencia_ambiental'] ) : ?>
		<table text-align: justify>
			<tr>
				<td colspan="4">
					<hr style="margin: 0">
				</td>
			</tr>
			<tr>
				<td><b>TIPOS DE VIOLENCIAS</b></td>
			</tr>
			<!-- VIOLENCIA FISICA -->
			<?php if ($atencion_datos['tipo_violencia_fisica']) { ?>
				<tr>
					<td valign="top" colspan="4">
						<u><b>Física</b></u>
					</td>
				</tr>
			<?php } ?>
			<?php if ($arrayTipoViolencia['violencia']['fisica']) { ?>
				<tr>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Frecuencia:</b>
						<?= $arrayTipoViolencia['violencia']['fisicaFrecuencia'] ? $arrayTipoViolencia['violencia']['fisicaFrecuencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="2">
						<b>Ocurrencia:</b>
						<?= $arrayTipoViolencia['violencia']['fisicaOcurrencia'] ? $arrayTipoViolencia['violencia']['fisicaOcurrencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Vigencia:</b>
						<?= $arrayTipoViolencia['violencia']['fisicaVigencia'] === '1' ? 'Si' : ($arrayTipoViolencia['violencia']['fisicaVigencia'] === '0' ? 'No' : '-') ?>
					</td>
				</tr>
				<?php foreach ($arrayTipoViolencia['violencia']['fisica'] as $violencia) { ?>
					<tr>
						<td style="padding-left: 15px;" valign="top" colspan="4">
							- <?= ($violencia['descripcion']) ?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>

			<!-- VIOLENCIA PSICOLOGICA -->
			<?php if ($atencion_datos['tipo_violencia_psicologica']) { ?>
				<tr>
					<td valign="top" colspan="4">
						<u><b>Psicológica</b></u>
					</td>
				</tr>
			<?php } ?>
			<?php if ($arrayTipoViolencia['violencia']['psicologica']) { ?>
				<tr>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Frecuencia:</b>
						<?= $arrayTipoViolencia['violencia']['psicologicaFrecuencia'] ? $arrayTipoViolencia['violencia']['psicologicaFrecuencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="2">
						<b>Ocurrencia:</b>
						<?= $arrayTipoViolencia['violencia']['psicologicaOcurrencia'] ? $arrayTipoViolencia['violencia']['psicologicaOcurrencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Vigencia:</b>
						<?= $arrayTipoViolencia['violencia']['psicologicaVigencia'] === '1' ? 'Si' : ($arrayTipoViolencia['violencia']['psicologicaVigencia'] === '0' ? 'No' : '-') ?>
					</td>
				</tr>
				<?php foreach ($arrayTipoViolencia['violencia']['psicologica'] as $violenciaPsicologica) { ?>
					<tr>
						<td style="padding-left: 15px;" colspan="4" valign="top">
							- <?= $violenciaPsicologica['descripcion'] ?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>

			<!-- VIOLENCIA SEXUAL -->
			<?php if ($atencion_datos['tipo_violencia_sexual']) { ?>
				<tr>
					<td valign="top" colspan="4">
						<u><b>Sexual</b></u>
					</td>
				</tr>
			<?php } ?>
			<?php if ($arrayTipoViolencia['violencia']['sexual']) { ?>
				<tr>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Frecuencia:</b>
						<?= $arrayTipoViolencia['violencia']['sexualFrecuencia'] ? $arrayTipoViolencia['violencia']['sexualFrecuencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="2">
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
						<td style="padding-left: 15px;" colspan="4" valign="top">
							- <?= $violenciaSexual['descripcion'] ?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>

			<!-- VIOLENCIA ECONOMICA PATRIMONIAL -->
			<?php if ($atencion_datos['tipo_violencia_economica_patrimonial']) { ?>
				<tr>
					<td valign="top" colspan="4">
						<u><b>Económica patrimonial</b></u>
					</td>
				</tr>
			<?php } ?>
			<?php if ($arrayTipoViolencia['violencia']['economicaPatrimonial']) { ?>
				<tr>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Frecuencia:</b>
						<?= $arrayTipoViolencia['violencia']['economicaPatrimonialFrecuencia'] ? $arrayTipoViolencia['violencia']['economicaPatrimonialFrecuencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="2">
						<b>Ocurrencia:</b>
						<?= $arrayTipoViolencia['violencia']['economicaPatrimonialOcurrencia'] ? $arrayTipoViolencia['violencia']['economicaPatrimonialOcurrencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Vigencia:</b>
						<?= $arrayTipoViolencia['violencia']['economicaPatrimonialVigencia'] === '1' ? 'Si'  : ($arrayTipoViolencia['violencia']['economicaPatrimonialVigencia'] === '0' ? 'No' : '-') ?>
					</td>
				</tr>
				<?php foreach ($arrayTipoViolencia['violencia']['economicaPatrimonial'] as $economicaPatrimonial) { ?>
					<tr>
						<td style="padding-left: 15px;" colspan="4" valign="top">
							- <?= $economicaPatrimonial['descripcion'] ?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>

			<!-- VIOLENCIA VIOLENCIA SIMBOLICA -->
			<?php if ($atencion_datos['tipo_violencia_simbolica']) { ?>
				<tr>
					<td valign="top" colspan="4">
						<u><b>Simbólica</b></u>
					</td>
				</tr>
			<?php } ?>
			<?php if ($arrayTipoViolencia['violencia']['simbolica']) { ?>
				<tr>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Frecuencia:</b>
						<?= $arrayTipoViolencia['violencia']['simbolicaFrecuencia'] ? $arrayTipoViolencia['violencia']['simbolicaFrecuencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="2">
						<b>Ocurrencia:</b>
						<?= $arrayTipoViolencia['violencia']['simbolicaOcurrencia'] ? $arrayTipoViolencia['violencia']['simbolicaOcurrencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Vigencia:</b>
						<?= $arrayTipoViolencia['violencia']['simbolicaVigencia'] === '1' ? 'Si'  : ($arrayTipoViolencia['violencia']['simbolicaVigencia'] === '0' ? 'No' : '-') ?>
					</td>
				</tr>
				<?php foreach ($arrayTipoViolencia['violencia']['simbolica'] as $simbolica) { ?>
					<tr>
						<td style="padding-left: 15px;" colspan="4" valign="top">
							- <?= $simbolica['descripcion'] ?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>

			<!-- VIOLENCIA NEGLIGENCIA ABANDONO -->
			<?php if ($atencion_datos['tipo_violencia_negligencia_abandono']) { ?>
				<tr>
					<td valign="top" colspan="4">
						<u><b>Negligencia - abandono</b></u>
					</td>
				</tr>
			<?php } ?>
			<?php if ($arrayTipoViolencia['violencia']['negligenciaAbandono']) { ?>
				<tr>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Frecuencia:</b>
						<?= $arrayTipoViolencia['violencia']['negligenciaAbandonoFrecuencia'] ? $arrayTipoViolencia['violencia']['negligenciaAbandonoFrecuencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="2">
						<b>Ocurrencia:</b>
						<?= $arrayTipoViolencia['violencia']['negligenciaAbandonoOcurrencia'] ? $arrayTipoViolencia['violencia']['negligenciaAbandonoOcurrencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Vigencia:</b>
						<?= $arrayTipoViolencia['violencia']['negligenciaAbandonoVigencia'] === '1' ? 'Si'  : ($arrayTipoViolencia['violencia']['negligenciaAbandonoVigencia'] === '0' ? 'No' : '-') ?>
					</td>
				</tr>
				<?php foreach ($arrayTipoViolencia['violencia']['negligenciaAbandono'] as $negligenciaAbandono) { ?>
					<tr>
						<td style="padding-left: 15px;" colspan="4" valign="top">
							- <?= $negligenciaAbandono['descripcion'] ?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>

			<!-- VIOLENCIA VIOLENCIA AMBIENTAL -->
			<?php if ($atencion_datos['tipo_violencia_ambiental']) { ?>
				<tr>
					<td valign="top" colspan="4">
						<u><b>Ambiental</b></u>
					</td>
				</tr>
			<?php } ?>
			<?php if ($arrayTipoViolencia['violencia']['ambiental']) { ?>
				<tr>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Frecuencia:</b>
						<?= $arrayTipoViolencia['violencia']['ambientalFrecuencia'] ? $arrayTipoViolencia['violencia']['ambientalFrecuencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="2">
						<b>Ocurrencia:</b>
						<?= $arrayTipoViolencia['violencia']['ambientalOcurrencia'] ? $arrayTipoViolencia['violencia']['ambientalOcurrencia'] : '-' ?>
					</td>
					<td style="padding-left: 15px;" valign="top" colspan="1">
						<b>Vigencia:</b>
						<?= $arrayTipoViolencia['violencia']['ambientalVigencia'] === '1' ? 'Si'  : ($arrayTipoViolencia['violencia']['ambientalVigencia'] === '0' ? 'No' : '-') ?>
					</td>
				</tr>
				<?php foreach ($arrayTipoViolencia['violencia']['ambiental'] as $ambiental) { ?>
					<tr>
						<td style="padding-left: 15px;" colspan="4" valign="top">
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
					<td><b>Nombre: </b><span><?= mb_strtoupper($hijo['apellido']) ?>, <?= mb_strtoupper($hijo['nombre']); ?> - <?= $hijo['documento']; ?> </td>
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
				<td colspan="2">
					<hr style="margin: 0">
				</td>
			</tr>
			<tr>
				<td colspan="2"><b>PERSONA AGRESORA</b></td>
			</tr>
			<div class="row">
				<?php foreach ($agresores as $agresor) : ?>
					<tr>
						<td style="padding-left: 15px;"><b>Agresor: </b><span><?= $agresor['dni'] ? $agresor['dni'] : ' '; ?> - <?= $agresor['nombre']; ?> <?= $agresor['apellido'] ?></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="1"><b>Género: </b><span><?= $agresor['generoDetalle']; ?></span></td>
						<td colspan="1"><b>Parentesco: </b><span><?= $agresor['parentesco']; ?></span></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b>Dato Denuncia: </b><span><?= $agresor['agresor_dato_denuncia']; ?></span></td>
						<td></td>
					</tr>
					<tr>
						<?php if ($agresor['agresor_dav'] == 1) : ?>
							<td style="padding-left: 15px;" colspan="2"><b>D.A.V. : </b><span>
									<?= $agresor['agresor_dav_datos']; ?></span></td>
							<td></td>
						<?php endif; ?>
					</tr>
					<tr>
						<?php if ($agresor['agresor_problematico'] == 1) : ?>
							<td style="padding-left: 15px;" colspan="2"><b> Agresor problemático: </b><span>
									<?= $agresor['agresor_consumo']; ?></span></td>
							<td></td>
						<?php endif; ?>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Escolaridad alcanzada: </b><span>
								<?= $agresor['escolaridadDetalle'] ? $agresor['escolaridadDetalle'] : ''  ?></span></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Es o fue funcionario/a público: </b><span>
								<?= $agresor['funcionario'] != null ? ($agresor['funcionario'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Realiza alguna actividad por la que le descuentan dinero: </b><span>
								<?= $agresor['desc_actividad'] != null ? ($agresor['desc_actividad'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Por esa actividad le descuentan para la jubilación: </b><span>
								<?= $agresor['desc_jubilacion'] != null ? ($agresor['desc_jubilacion'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="1"><b> Acceso a armas de fuego: </b><span>
								<?= $agresor['acceso_armas'] != null ? ($agresor['acceso_armas'] == 1 ? 'Si' : 'No') : '-' ?></span></td>

						<td style="padding-left: 15px;" colspan="1"><b> Antecedentes penales: </b><span>
								<?= $agresor['antecedente_penales'] != null ? ($agresor['antecedente_penales'] == 1 ? 'Si' : 'No') : '-' ?></span></td>

					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Antecedentes de violencia con parejas o ex parejas: </b><span>
								<?= $agresor['antecedente_violencia'] != null ? ($agresor['antecedente_violencia'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Antecendentes de violación de medidas de restrición: </b><span>
								<?= $agresor['antecedente_restricciones'] != null ? ($agresor['antecedente_restricciones'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Vínculo con actividades ilícitas: </b><span>
								<?= $agresor['vinculo_ilicito'] != null ? ($agresor['vinculo_ilicito'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Vínculo con personal de seguridad: </b><span>
								<?= $agresor['vinculoPersonalSeguridad'] ? $agresor['vinculoPersonalSeguridad'] : '-'; ?></span></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;" colspan="2"><b> Consumo problemático: </b><span>
								<?= $agresor['consumo_problematico'] != null ? ($agresor['consumo_problematico'] == 1 ? 'Si' : 'No') : '-' ?></span></td>
						<td></td>
					</tr>
					<tr>
						<?php if ($agresor['consumo_problematico'] == 1) : ?>
							<td style="padding-left: 15px;" colspan="2"><b> Tipos de consumo problemático: </b><span>
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
		<?php if ($movimientos != []) : ?>
			<tr>
				<td colspan="3">
					<hr style="margin: 0">
				</td>
			</tr>
			<tr>
				<td colspan="3"><b>MOVIMIENTOS</b></td>
			</tr>
			<?php foreach ($movimientos as $clave => $mov) : ?>
				<tr>
					<td style="padding-left: 15px;" colspan="1"><b>Tipo de Movimiento: </b><?= $mov['tipo_movimiento']; ?></td>
					<td colspan="1"><b>Fecha: </b><?= $mov['fecha']; ?></td>
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
			<td colspan="3"><b>ABORDAJES COMPLEMENTARIOS</b></td>
		</tr>
	</table>

	<p class="parrafo" style="text-align:justify"><b>Abordaje: </b><span><?= $atencion_datos['abordaje_complementario']; ?></span></p>

</body>

</html>