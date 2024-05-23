<?php

use app\models\Sds_800_atencion_am;
use app\models\Sds_800_llamada;
use app\models\Sds_800_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Sds_com_persona;
use yii\helpers\Url;

$idllamada = $_GET['idllamada'];

$query = new yii\db\Query;
$query->select(["
	case  llamada.afectado_tratamiento
		WHEN 0 then 'Paciente Adicciones'
		WHEN 1 then 'Paciente Salud Mental'
		WHEN 2 then 'Paciente Duales'
	END llamadaTratamiento,
	case llamada.area	
		WHEN 0 then 'Situación de Calle'
        WHEN 1 then 'Familia'
        WHEN 2 then 'Adultos Mayores'
        WHEN 3 then 'Interior'
        WHEN 4 then 'Violencia'
	END llamadaArea,
	case llamada.estado	
		WHEN 0 then 'Pendiente de Evaluación'
		WHEN 1 then 'No Corresponde'
		WHEN 2 then 'Derivada'
		WHEN 3 then 'Atendida'
		WHEN 4 then 'Cerrada'
		WHEN 5 then 'Situación Despejada'
	END as llamadaEstado,
	llamada.solicitante as llamadasolicitante, llamada.institucion as llamadainstitucion,llamada.vinculo as llamadavinculo,llamada.detalle as llamadadetalle,llamada.afectado_dni as llamadaafectadodni,llamada.afectado_nombre as llamadaafectadonombre,llamada.afectado_apodo as llamadaafectadoapodo,llamada.derivacion_referente as llamadaderivacionreferente,llamada.derivacion_detalle as llamadaderivaciondetalle,llamada.cierre_detalle as llamadacierredetalle,DATE_FORMAT(llamada.fecha_hora,'%d/%m/%Y %H:%i') as fechaLlamada,DATE_FORMAT(llamada.derivacion_fecha,'%d/%m/%Y') as fechaDerivacion, DATE_FORMAT(llamada.cierre_fecha,'%d/%m/%Y') as fechaCierre,llamada.latitud as llamadalatitud,llamada.longitud as llamadalongitud,llamada.direccion as llamadadireccion,
	persona0800.domicilio as 800domicilio,persona0800.telefono as 800telefono,localidad0800.descripcion as 800localidad,provincia0800.descripcion as 800provincia,
	nacionalidad.descripcion as nacionalidad,
	genero.descripcion as genero,
	documento_tipo.descripcion as documentotipo,
	atencion.idllamada as idatencion,
	tipo.descripcion as tipo,
	derivacion.descripcion as derivaciondetalle, derivacion.direccion as derivaciondireccion, derivacion.telefonos as derivaciontelefonos,
    UPPER(usuario.nombre) as usuarionombre, UPPER(usuario.apellido) as usuarioapellido,
	llamada.idderivacion as idderivacion, llamada.idorigen,
	UPPER(persona.nombre) as personanombre, UPPER(persona.apellido) as personaapellido,DATE_FORMAT(persona.fecha_nacimiento,'%d/%m/%Y') personafechanacimiento,persona.documento as personadocumento"])
	->from(["sds_800_llamada llamada"])
	->join("inner join", "sds_800_persona as persona0800", "persona0800.idpersona=llamada.idpersona")
	->join("inner join", "sds_com_localidad as localidad0800", "persona0800.idlocalidad=localidad0800.idlocalidad")
	->join("inner join", "sds_com_provincia as provincia0800", "localidad0800.idprovincia=provincia0800.idprovincia")
	->join("inner join", "sds_com_persona as persona", "persona.idpersona=persona0800.idpersona")
	->join("left join", "sds_800_llamada as atencion", "llamada.idllamada=atencion.idllamada")
	->join("left join", "sds_800_derivacion as derivacion", "llamada.idderivacion=derivacion.idderivacion")
	->join("left join", "sds_com_configuracion as nacionalidad", "persona.nacionalidad=nacionalidad.idconfiguracion")
	->join("left join", "sds_com_configuracion as genero", "persona.genero=genero.idconfiguracion")
	->join("left join", "sds_com_configuracion as documento_tipo", "persona.documento_tipo=documento_tipo.idconfiguracion")
	->join("left join", "sds_com_configuracion as tipo", "tipo.idconfiguracion=llamada.tipo")
	->join("left join", "mds_seg_usuario as usuario", "usuario.idusuario=llamada.idusuario")
	->join("left join", "sds_800_llamada as origen", "origen.idllamada=llamada.idorigen")
	->where(["llamada.idllamada" => $idllamada]);

$command = $query->createCommand();
$llamada_datos = $command->queryOne();


// Si la llamada tiene atención, se realiza la consulta para obtener todos sus datos
if ($llamada_datos['idatencion']) {
	$atencion_datos = Sds_800_atencion_am::findOne($llamada_datos['idatencion']);
	if ($atencion_datos != null) {
		$model_com_persona_atencion = Sds_com_persona::findOne($atencion_datos->idpersona);
		$model_800_persona_atencion = Sds_800_persona::findOne($atencion_datos->idpersona);
		$atencion_datos->dni = $model_com_persona_atencion->documento;
		$atencion_datos->nombre = $model_com_persona_atencion->nombre;
		$atencion_datos->apellido = $model_com_persona_atencion->apellido;
		$atencion_datos->localidad = Sds_com_localidad::findOne($model_800_persona_atencion->idlocalidad)->descripcion;
		$atencion_datos->telefono = $model_800_persona_atencion->telefono;
		$atencion_actividades = Sds_com_configuracion::find()->where(["idatencionam" => $llamada_datos['idatencion']])
			->innerJoin('sds_800_am_recreacion', 'sds_com_configuracion.idconfiguracion = sds_800_am_recreacion.recreacion')->all();
	}
}

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
			<h4 style="margin: 0; font-weight: bold;">REPORTE DE LLAMADA</h4>
			<p><span><?= $llamada_datos['llamadaArea'] ?></span></p>
			<hr style="margin: 0">
		</div>
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
				<td><b>Solicitante: </b><span><?= $llamada_datos['llamadasolicitante'] ? "Si" : "No" ?></span></td>
				<td></td>
				<td><b>Domicilio: </b><span><?= $llamada_datos['800domicilio']; ?></span></td>
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
			<?php if ($atencion_datos) : ?>
				<tr>
					<th><br></th>
				</tr>
				<tr style="background-color: #dddddd;">
					<th class="titulo">
						<h5>DATOS DE LA ATENCIÓN: </h5>
					</th>
				</tr>
				<tr>
					<td><b>Fecha de Atención: </b><span><?= date_format(date_create($atencion_datos['fecha_hora']), 'd-m-Y'); ?></span></td>
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
						<b>Documento: </b><span><?= $atencion_datos['dni']; ?></span>
					</td>
					<td></td>
					<td>
						<b>Nombre: </b><span> <?= $atencion_datos['apellido']; ?>, <?= $atencion_datos['nombre']; ?></span>
					</td>
				</tr>
				<tr>
					<td><b>Teléfono: </b><span><?= $atencion_datos['telefono']; ?></span></td>
					<td></td>
					<td><b>Localidad: </b><span><?= $atencion_datos['localidad']; ?></span></td>
				</tr>
				<tr>
					<td><b>Teléfono Referente: </b><span><?= $atencion_datos['telefono_referente']; ?></span></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>DETALLE ATENCIÓN </b></td>
				</tr>
				<tr>
					<td colspan="3"><b>Motivo de la demanda: </b><span><?= $atencion_datos['demanda']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Estuvo atendido por algun equipo profesional previo a esta llamada? </b>
						<span>
							<?php switch ($atencion_datos['atencion_previa']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<?php if ($atencion_datos['atencion_previa'] == 1) : ?>
					<tr>
						<td colspan="3"><b>¿De qué institución? </b><span><?= $atencion_datos['institucion']; ?></span></td>
					</tr>
					<tr>
						<td colspan="3"><b>¿Qué profesionales? </b><span><?= $atencion_datos['profesionales']; ?></span></td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>

				<tr>
					<td colspan="3"><b>DATOS DE LA VIVIENDA </b></td>
				</tr>
				<tr>
					<td><b>¿Servicio de recolección de basura? </b>
						<span>
							<?php switch ($atencion_datos['basura']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
					<td></td>
					<td><b>¿Servicio de cable? </b>
						<span>
							<?php switch ($atencion_datos['cable']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Servicio de Internet? </b>
						<span>
							<?php switch ($atencion_datos['internet']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>SITUACIÓN DEL ADULTO MAYOR </b></td>
				</tr>
				<tr>
					<td><b>¿Tiene red de familiares? </b>
						<span>
							<?php switch ($atencion_datos['familiares']) {
								case 0:
									echo 'No tiene';
									break;
								case 1:
									echo 'Si tiene y sin vínculos';
									break;
								case 2:
									echo 'Si tiene y con vínculos';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td><b>¿Tiene red de sociales? </b>
						<span>
							<?php switch ($atencion_datos['sociales']) {
								case 0:
									echo 'No tiene';
									break;
								case 1:
									echo 'Vecinos';
									break;
								case 2:
									echo 'Propietario de alquiler';
									break;
								case 3:
									echo 'Ref. institucional de OSCPM';
									break;
								case 4:
									echo 'Iglesia';
									break;
								case 5:
									echo 'Obra Social';
									break;
								case 6:
									echo 'Barrial';
									break;
								case 7:
									echo 'Otros';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<?php if ($atencion_datos['sociales'] != 0) : ?>
					<tr>
						<td colspan="3" style="text-align:justify">
							<b>¿Cuál? </b><?= $atencion_datos['sociales_detalle']; ?></span>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="3"><b>Si le sucede algún emergente, ¿A quién acudo de lo antes mencionado? </b>
						<span>
							<?php switch ($atencion_datos['emergente']) {
								case 0:
									echo 'Red Familiar';
									break;
								case 1:
									echo 'Red Social';
									break;
								case 2:
									echo 'Otra';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align:justify">
						<b>Detalle: </b><?= $atencion_datos['emergente_detalle']; ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Se encuentra realizando tratamiento psicológico? </b>
						<span>
							<?php switch ($atencion_datos['psicologico']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Se encuentra realizando tratamiento psiquiátrico? </b>
						<span>
							<?php switch ($atencion_datos['psiquiatrico']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Administra su dinero? </b>
						<span>
							<?php switch ($atencion_datos['administra_dinero']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<?php if ($atencion_datos['administra_dinero'] == 2) : ?>
					<tr>
						<td colspan="3" style="text-align:justify">
							<b>En caso de no, ¿Quién lo administra? </b><?= $atencion_datos['detalle_dinero']; ?></span>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="3"><b>¿Recibe algun PLAN o PROGRAMA del estado provincial? </b>
						<span>
							<?php switch ($atencion_datos['plan']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<?php if ($atencion_datos['plan'] == 1) : ?>
					<tr>
						<td colspan="3" style="text-align:justify">
							<b>En caso afirmativo, ¿Cuál? </b><?= $atencion_datos['detalle_plan']; ?></span>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="3"><b>¿Le gustaría participar en algún centro de personas mayores? </b>
						<span>
							<?php switch ($atencion_datos['centro']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Participa de alguna actividad lúdico-recreativa? </b>
						<span>
							<?php switch ($atencion_datos['recreacion']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿En que actividad o actividades le gustaría participar?</b></td>
				</tr>
				<?php if ($atencion_actividades) : ?>
					<tr>
						<td colspan="3">
							<ul>
								<?php foreach ($atencion_actividades as $actividad) : ?>
									<li><?= $actividad->descripcion; ?></li>
								<?php endforeach; ?>
							</ul>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>EVALUACIÓN FUNCIONAL </b></td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Se encuentra orientado en tiempo y espacio? </b>
						<span>
							<?php switch ($atencion_datos['orientado']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Es dependiente o independiente? </b>
						<span>
							<?php switch ($atencion_datos['dependiente']) {
								case 0:
									echo 'Totalmente dependiente';
									break;
								case 1:
									echo 'Dependiente en algunas o varias actividades';
									break;
								case 2:
									echo 'Independiente';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Se encuentra intoxicado? </b>
						<span>
							<?php switch ($atencion_datos['intoxicado']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Presenta delirios y/o alucinaciones? </b>
						<span>
							<?php switch ($atencion_datos['delirios']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Se encuentra violentado? </b>
						<span>
							<?php switch ($atencion_datos['violentado']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>¿Se expresa de manera clara? </b>
						<span>
							<?php switch ($atencion_datos['expresion']) {
								case 0:
									echo 'Sin Datos';
									break;
								case 1:
									echo 'Si';
									break;
								case 2:
									echo 'No';
									break;
							} ?>
						</span>
					</td>
				</tr>
		</table>

		<p class="parrafo" style="text-align:justify"><b>Observaciones: </b><span><?= $atencion_datos['observaciones']; ?></span></p>

		<table>
			<?php if ($atencion_datos['archivo_salud']) : ?>
				<tr>
					<td colspan="3" style="text-align:justify">
						<b>Esta Llamada Tiene Archivo de Salud Adjunto. </b>
					</td>
				</tr>
			<?php endif; ?>
			<?php if ($atencion_datos['archivo_seguridad']) : ?>
				<tr>
					<td colspan="3" style="text-align:justify">
						<b>Esta Llamada Tiene Archivo de Seguridad Adjunto. </b>
					</td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ($llamada_datos['tipo'] || $llamada_datos['idderivacion']) : ?>
			<tr>
				<th><br></th>
			</tr>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5> Detalle de la Derivación: </h5>
				</th>
			</tr>
		</table>

		<p class="parrafo" style="text-align:justify"><b>Descripción: </b><?= $llamada_datos['llamadaderivaciondetalle']; ?></p>

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
		<?php endif; ?>
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
								case 0:
									echo 'Situación de Calle';
									break;
								case 1:
									echo 'Familia';
									break;
								case 2:
									echo 'Adultos Mayores';
									break;
								case 3:
									echo 'Interior';
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

</html>