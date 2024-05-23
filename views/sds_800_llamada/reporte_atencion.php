<?php

use app\models\Sds_800_llamada;
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
llamada.solicitante as llamadasolicitante, llamada.institucion as llamadainstitucion,llamada.vinculo as llamadavinculo,llamada.detalle as llamadadetalle,
llamada.afectado_dni as llamadaafectadodni,llamada.afectado_nombre as llamadaafectadonombre,
llamada.afectado_apodo as llamadaafectadoapodo,llamada.derivacion_referente as llamadaderivacionreferente,
llamada.derivacion_detalle as llamadaderivaciondetalle,llamada.cierre_detalle as llamadacierredetalle,
DATE_FORMAT(llamada.fecha_hora,'%d/%m/%Y %H:%i') as fechaLlamada,DATE_FORMAT(llamada.derivacion_fecha,'%d/%m/%Y') as fechaDerivacion, 
DATE_FORMAT(llamada.cierre_fecha,'%d/%m/%Y') as fechaCierre,llamada.latitud as llamadalatitud,
llamada.longitud as llamadalongitud,llamada.direccion as llamadadireccion,
persona0800.domicilio as 800domicilio,persona0800.telefono as 800telefono,
localidad0800.descripcion as 800localidad,provincia0800.descripcion as 800provincia,
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
	$query = new yii\db\Query;
	$query->select(["
		CASE atencion.beneficio WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END beneficio,
		CASE atencion.sabe_leer WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END sabe_leer,
		CASE atencion.nivel_estudio
			WHEN 0 THEN 'Sin Datos'
			WHEN 1 THEN 'Primario Incompleto'
			WHEN 2 THEN 'Primario Completo'
			WHEN 3 THEN 'Secundario Incompleto'
			WHEN 4 THEN 'Secundario Completo'
			WHEN 5 THEN 'Terciario/Otro Incompleto'
			WHEN 6 THEN 'Terciario/Otro Completo'
		END nivel_estudio,
		CASE atencion.trabajo WHEN 0 then 'No' WHEN 1 then 'Formal' WHEN 2 then 'Informal' END trabajo,
		CASE atencion.antiguedad
			WHEN 0 THEN 'Sin Datos'
			WHEN 1 THEN 'menos de 1 años'
			WHEN 2 THEN 'entre 1 y 5 años'
			WHEN 3 THEN 'mas de 5 años'
		END antiguedad,
		CASE atencion.ubicacion_anterior
			WHEN 0 THEN 'Sin Datos'
			WHEN 1 THEN 'En la casa de un familiar'
			WHEN 2 THEN 'Alquilaba por cuenta propia'
			WHEN 3 THEN 'Le alquilaba algún efector del estado'
			WHEN 4 THEN 'Otro'
		END ubicacion_anterior,
		CASE atencion.atencion_anterior WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END atencion_anterior,		
		CASE atencion.asistencia_estado WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END asistencia_estado,
		CASE atencion.familia
			WHEN 0 THEN 'Sin Datos'
			WHEN 1 THEN 'Si tiene y con vínculos adecuados'
			WHEN 2 THEN 'Si tiene y sin vínculos adecuados'
			WHEN 3 THEN 'No tiene'
		END familia,
		CASE atencion.sentimiento
			WHEN 0 THEN 'Sin Datos'
			WHEN 1 THEN 'Bien'
			WHEN 2 THEN 'Mal'
			WHEN 3 THEN 'Es una eleccion de vida'
		END sentimiento,
		CASE atencion.orientado WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END orientado,
		CASE atencion.evaluacion_funcional
			WHEN 0 THEN 'Sin Datos'
			WHEN 1 THEN 'Totalmente Dependiente'
			WHEN 2 THEN 'Dependiente en Algunas o Varias Actividades'
			WHEN 3 THEN 'Independiente'
			WHEN 4 THEN 'Otro'
		END evaluacion_funcional,

		CASE atencion.intoxicado WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END intoxicado,
		CASE atencion.alucinaciones WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END alucinaciones,
		CASE atencion.violentado WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END violentado,
		CASE atencion.expresar WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END expresar,
		CASE atencion.tratamiento WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END tratamiento,
		DATE_FORMAT(atencion.fecha_hora,'%d/%m/%Y') as fechaatencion, atencion.causa_situacion, atencion.edad, atencion.trabajo_detalle, atencion.ubicacion_anterior_detalle, atencion.atencion_anterior_institucion, atencion.atencion_anterior_profesional,
		atencion.asistencia_estado_detalle, atencion.evaluacion_funcional_detalle, atencion.tratamiento_profesional, atencion.tratamiento_institucion,		
		atencion.archivo_salud, atencion.observaciones, atencion.persona_datos,
		atencion.causa_situacion as causa_situacion,
		persona0800.domicilio as 800domicilio,persona0800.telefono as 800telefono,
		localidad0800.descripcion as 800localidad,
		provincia0800.descripcion as 800provincia,

		localidad0800oriundo.descripcion as 800localidadoriundo,
		provincia0800oriundo.descripcion as 800provinciaoriundo,

		nacionalidad2.descripcion as nacionalidad,
		genero2.descripcion as genero,
		genero_autopercibido2.descripcion as genero_autopercibido,
		documento_tipo.idconfiguracion as documentotipo,
		ttipo_ayuda.descripcion as eltipo_ayuda,
		texpectativa_corto_plazo.descripcion as expectativa_corto_plazo,
		abandono.descripcion as motivo_abandono2,
		situacion_salud.descripcion as situacion_saludcampo,		
		consumo_problematico.descripcion as consumo_problematicocampo,
		capacidad_limitada.descripcion as capacidad_limitadacampo,
		situacion_laboral.descripcion as situacion_laboralcampo,
		atencion.oficio as oficio,
		aportes_economicos.descripcion as aportes_economicoscampo,
		
		persona.nombre as personanombre,persona.apellido as personaapellido,DATE_FORMAT(persona.fecha_nacimiento,'%d/%m/%Y') personafechanacimiento,persona.documento as personadocumento"])
		->from(["sds_800_atencion atencion"])
		->join("left join", "sds_800_persona as persona0800", "persona0800.idpersona=atencion.idpersona")
		->join("left join", "sds_com_localidad as localidad0800", "persona0800.idlocalidad=localidad0800.idlocalidad")
		->join("left join", "sds_com_provincia as provincia0800", "localidad0800.idprovincia=provincia0800.idprovincia")
		->join("left join", "sds_com_localidad as localidad0800oriundo", "persona0800.idlocalidadoriundo=localidad0800oriundo.idlocalidad")
		->join("left join", "sds_com_provincia as provincia0800oriundo", "localidad0800oriundo.idprovincia=provincia0800oriundo.idprovincia")
		->join("left join", "sds_com_persona as persona", "persona.idpersona=persona0800.idpersona")
		->join("left join", "sds_com_configuracion as ttipo_ayuda", "atencion.tipo_ayuda=ttipo_ayuda.idconfiguracion")
		->join("left join", "sds_com_configuracion as texpectativa_corto_plazo", "atencion.expectativa_corto_plazo=texpectativa_corto_plazo.idconfiguracion")
		->join("left join", "sds_com_configuracion as nacionalidad2", "persona.nacionalidad=nacionalidad2.idconfiguracion")
		->join("left join", "sds_com_configuracion as genero2", "persona.genero=genero2.idconfiguracion")
		->join("left join", "sds_com_configuracion as genero_autopercibido2", "persona0800.idgeneroautopercibido=genero_autopercibido2.idconfiguracion")
		->join("left join", "sds_com_configuracion as documento_tipo", "persona.documento_tipo=documento_tipo.idconfiguracion")
		->join("left join", "mds_seg_usuario as usuario", "usuario.idusuario=atencion.idusuario")
		->join("left join", "sds_com_configuracion as abandono", "atencion.motivo_abandono=abandono.idconfiguracion")
		->join("left join", "sds_com_configuracion as situacion_salud", "atencion.situacion_salud=situacion_salud.idconfiguracion")
		->join("left join", "sds_com_configuracion as consumo_problematico", "atencion.consumo_problematico=consumo_problematico.idconfiguracion")
		->join("left join", "sds_com_configuracion as capacidad_limitada", "atencion.capacidad_limitada=capacidad_limitada.idconfiguracion")
		->join("left join", "sds_com_configuracion as situacion_laboral", "atencion.r_situacion_laboral=situacion_laboral.idconfiguracion")
		->join("left join", "sds_com_configuracion as aportes_economicos", "atencion.aportes_economicos=aportes_economicos.idconfiguracion")
		->where(["atencion.idllamada" => $llamada_datos['idatencion']]);

	$command = $query->createCommand();
	$atencion_datos = $command->queryOne();
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
						<h5> Detalle Atencion: </h5>
					</th>
				</tr>
				<tr>
					<td colspan="3"><b>Fecha de Atención: </b><span><?= $atencion_datos['fechaatencion']; ?></span></td>
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
						<b>Documento: </b><span><?= $atencion_datos['personadocumento']; ?></span>
					</td>
					<td></td>
					<td>
						<b>Nombre: </b><span><?= $atencion_datos['personanombre']; ?>, <?= $atencion_datos['personaapellido']; ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<b>Fecha de Nacimiento: </b><span><?= $atencion_datos['personafechanacimiento']; ?></span>
					</td>
					<td></td>
					<td><b>Nacionalidad: </b><span><?= $atencion_datos['nacionalidad']; ?></span></td>
				</tr>
				<tr>
					<td><b>Género: </b><span><?= $atencion_datos['genero']; ?></span></td>
					<td></td>
					<td><b>Género Autopercibido: </b><span><?= $atencion_datos['genero_autopercibido']; ?></span></td>

				</tr>
				<tr>
					<td><b>Provincia: </b><span><?= $atencion_datos['800provincia']; ?></span></td>
					<td></td>
					<td colspan="3"><b>Localidad: </b><span><?= $atencion_datos['800localidad']; ?></span></td>
				</tr>
				<tr>
					<td><b>Provincia Oriundo: </b><span><?= $atencion_datos['800provinciaoriundo']; ?></span></td>
					<td></td>
					<td colspan="3"><b>Localidad Oriundo: </b><span><?= $atencion_datos['800localidadoriundo']; ?></span></td>
				</tr>

				<tr>
					<td><b>Teléfono: </b><span><?= $atencion_datos['800telefono']; ?></span></td>
					<td></td>
					<td colspan="3"><b>Edad que dice tener: </b><span><?= $atencion_datos['edad']; ?></span></td>
				</tr>
				<tr>
					<?php

					$porciones = explode("/", $atencion_datos['personafechanacimiento']);
					$fecha_nueva = $porciones[2] . '-' . $porciones[1] . '-' . $porciones[0];


					$nacimiento = new DateTime($fecha_nueva);

					$ahora = new DateTime(date("Y-m-d"));

					$diferencia = $ahora->diff($nacimiento);
					//$diferencia='P'.$newDate.'P';

					?>
					<td><b>Edad actual: </b><span>
							<?php
							echo $diferencia->format("%y");

							?>
						</span></td>

				</tr>

				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>ASPECTOS SOCIALES </b></td>
				</tr>
				<tr>
					<td colspan="3">
						<p class="parrafo" style="text-align:justify"><b>Motivo de situación de calle: </b><span><?= $atencion_datos['causa_situacion']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align:justify"><b>Red social y familiar: </b><span><?= $atencion_datos['familia']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" style="text-align:justify"><b>Tipo de ayuda que solicita: </b><span><?= $atencion_datos['eltipo_ayuda']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" style="text-align:justify"><b>Expectativa a corto plazo: </b><span><?= $atencion_datos['expectativa_corto_plazo']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" style="text-align:justify"><b>Evaluación Funcional: </b><span><?= $atencion_datos['evaluacion_funcional']; ?></span></td>
				</tr>
				<?php if ($atencion_datos['evaluacion_funcional'] != 'Sin Datos') : ?>
					<tr>
						<td colspan="3" style="text-align:justify"><b>Detalle Evaluación Funcional: </b><span><?= $atencion_datos['evaluacion_funcional_detalle']; ?></span></td>
					</tr>

				<?php endif; ?>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>ASPECTOS HABITACIONALES </b></td>
				</tr>


				<tr>
					<td colspan="3">
						<p class="parrafo" style="text-align:justify"><b>Hace cuanto se encuentra en situación de calle? </b><span><?= $atencion_datos['antiguedad']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<p class="parrafo" style="text-align:justify"><b>Último lugar de pernocte: </b><span><?= $atencion_datos['ubicacion_anterior']; ?></span></p>
					</td>
				</tr>
				<tr style="display: <?php if ($atencion_datos['ubicacion_anterior'] == 0) {
										echo "none";
									} else {
										echo "block";
									} ?>">
					<td colspan="3" style="text-align:justify"><b>Detalle Ubicación Anterior: </b><span><?= $atencion_datos['ubicacion_anterior_detalle']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3">
						<p class="parrafo" style="text-align:justify"><b>Motivo de abandono de alojamiento previo: </b><span><?= $atencion_datos['motivo_abandono2']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<p class="parrafo" style="text-align:justify"><b>Atención Anterior: </b><span><?= $atencion_datos['atencion_anterior']; ?></span></p>
					</td>
				</tr>


				<?php if ($atencion_datos['atencion_anterior'] == 'Si') : ?>
					<tr>
						<td colspan="3">
							<p class="parrafo" style="text-align:justify"><b>Institución: </b><span><?= $atencion_datos['atencion_anterior_institucion']; ?></span></p>

						</td>
					</tr>
				<?php endif; ?>
				<?php if ($atencion_datos['atencion_anterior'] == 'Si') : ?>
					<tr>
						<td colspan="3">
							<p class="parrafo" style="text-align:justify"><b>Profesional: </b><span><?= $atencion_datos['atencion_anterior_profesional']; ?></span></p>

						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="3">
						<p class="parrafo" style="text-align:justify"><b>Recorrido Institucional: </b><span><?= $atencion_datos['asistencia_estado']; ?></span></p>
					</td>
				</tr>
				<?php if ($atencion_datos['asistencia_estado'] == 'Si') : ?>
					<tr>
						<td colspan="3">
							<p class="parrafo" style="text-align:justify"><b>Cuál?: </b><span><?= $atencion_datos['asistencia_estado_detalle']; ?></span></p>

						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>ASPECTOS DE SALUD </b></td>
				</tr>
				<tr>
					<td colspan="1">
						<p class="parrafo" style="text-align:justify"><b>¿Cómo se siente?: </b><span><?= $atencion_datos['sentimiento']; ?></span></p>
					</td>
					<td colspan="2">
						<p class="parrafo" style="text-align:justify"><b>Situación Salud: </b><span><?= $atencion_datos['situacion_saludcampo']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="1">
						<p class="parrafo" style="text-align:justify"><b>Consumo Problemático: </b><span><?= $atencion_datos['consumo_problematicocampo']; ?></span></p>
					</td>
					<td colspan="2">
						<p class="parrafo" style="text-align:justify"><b>Capacidad Limitada: </b><span><?= $atencion_datos['capacidad_limitadacampo']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="1">
						<p class="parrafo" style="text-align:justify"><b>¿Se encuentra orientado? </b><span><?= $atencion_datos['orientado']; ?></span></p>
					</td>
					<td colspan="2">
						<p class="parrafo" style="text-align:justify"><b>Intoxicado: </b><span><?= $atencion_datos['intoxicado']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="1">
						<p class="parrafo" style="text-align:justify"><b>Violentado </b><span><?= $atencion_datos['violentado']; ?></span></p>
					</td>
					<td colspan="2">
						<p class="parrafo" style="text-align:justify"><b>¿Se Puede Expresar? </b><span><?= $atencion_datos['expresar']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="1">
						<p class="parrafo" style="text-align:justify"><b>¿Posee Beneficio y/u Obra Social? </b><span><?= $atencion_datos['beneficio']; ?></span></p>
					</td>
					<td colspan="2">
						<p class="parrafo" style="text-align:justify"><b>¿Se encuentra en Tratamiento? </b><span><?= $atencion_datos['tratamiento']; ?></span></p>
					</td>
				</tr>
				<?php if ($atencion_datos['tratamiento'] == 'Si') : ?>
					<tr>
						<td colspan="1">
							<p class="parrafo" style="text-align:justify"><b>Institución </b><span><?= $atencion_datos['tratamiento_institucion']; ?></span></p>
						</td>
						<td colspan="2">
							<p class="parrafo" style="text-align:justify"><b>Profesional </b><span><?= $atencion_datos['tratamiento_profesional']; ?></span></p>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="4">
						<p class="parrafo" style="text-align:justify"><b>Alucinaciones </b><span><?= $atencion_datos['alucinaciones']; ?></span></p>
					</td>

				</tr>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>ASPECTOS ECONOMICOS </b></td>
				</tr>
				<tr>
					<td colspan="1">
						<p class="parrafo" style="text-align:justify"><b>Nivel de Estudio </b><span><?= $atencion_datos['nivel_estudio']; ?></span></p>
					</td>
					<td colspan="2">
						<p class="parrafo" style="text-align:justify"><b>¿Sabe Leer? </b><span><?= $atencion_datos['sabe_leer']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="1">
						<p class="parrafo" style="text-align:justify"><b>¿Tiene Trabajo? </b><span><?= $atencion_datos['trabajo']; ?></span></p>
					</td>
				</tr>
				<?php if ($atencion_datos['trabajo'] != 'No') : ?>
					<tr>
						<td colspan="3">
							<p class="parrafo" style="text-align:justify"><b>Detalle </b><span><?= $atencion_datos['trabajo_detalle']; ?></span></p>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td colspan="1">
						<p class="parrafo" style="text-align:justify"><b>Situación Laboral </b><span><?= $atencion_datos['situacion_laboralcampo']; ?></span></p>
					</td>
					<td colspan="2">
						<p class="parrafo" style="text-align:justify"><b>Oficio </b><span><?= $atencion_datos['oficio']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<p class="parrafo" style="text-align:justify"><b>Aportes Económicos </b><span><?= $atencion_datos['aportes_economicoscampo']; ?></span></p>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>INFORMACIÓN COMPLEMENTARIA</b></td>
				</tr>


		</table>

		<p class="parrafo" style="text-align:justify"><b>Observaciones: </b><span><?= $atencion_datos['observaciones']; ?></span></p>

		<table>
			<?php if ($atencion_datos['archivo_salud']) : ?>
				<tr>
					<td colspan="3" style="text-align:justify">
						<b>Esta Llamada Tiene Archivo Adjunto. </b>
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