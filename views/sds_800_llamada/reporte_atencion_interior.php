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
	$query = new yii\db\Query;
	$query->select(["
		CASE atencion.lugar_intervencion
			WHEN 0 THEN 'Comisaria'
			WHEN 1 THEN 'Escuela'
			WHEN 2 THEN 'Centro de Salud/Hospital'
			WHEN 3 THEN 'Familia - Admisión'
			WHEN 4 THEN 'Familia - Ley 2302'
			WHEN 5 THEN 'Otro'
		END lugar_intervencion,
	    atencion.lugar_especificacion, atencion.defensora, atencion.plan_accion,atencion.archivo_adjunto,
		DATE_FORMAT(atencion.fecha_intervencion,'%d/%m/%Y') as fechaatencion,
		persona0800.telefono as 800telefono,localidad0800.descripcion as 800localidad,provincia0800.descripcion as 800provincia,
		UPPER(persona.nombre) as personanombre, UPPER(persona.apellido) as personaapellido,persona.documento as personadocumento,
		referente0800.telefono as referente800telefono,referente0800.domicilio as referente800domicilio,referentelocalidad0800.descripcion as referente800localidad,referenteprovincia0800.descripcion as referente800provincia,
		referente.nombre as referentenombre,referente.apellido as referenteapellido,referente.documento as referentedocumento,DATE_FORMAT(referente.fecha_nacimiento,'%d/%m/%Y') as referentefechanacimiento,
		nacionalidad.descripcion as nacionalidad,
		parentezco.descripcion as parentezco,
		genero.descripcion as genero,
		documento_tipo.descripcion as documentotipo"])
		->from(["sds_800_atencion_interior atencion"])
		->join("left join", "sds_800_persona as persona0800", "persona0800.idpersona=atencion.idpersona")
		->join("left join", "sds_com_localidad as localidad0800", "persona0800.idlocalidad=localidad0800.idlocalidad")
		->join("left join", "sds_com_provincia as provincia0800", "localidad0800.idprovincia=provincia0800.idprovincia")
		->join("left join", "sds_com_persona as persona", "persona.idpersona=persona0800.idpersona")
		->join("left join", "sds_800_persona as referente0800", "referente0800.idpersona=atencion.idpersona_referente")
		->join("left join", "sds_com_localidad as referentelocalidad0800", "referente0800.idlocalidad=referentelocalidad0800.idlocalidad")
		->join("left join", "sds_com_provincia as referenteprovincia0800", "referentelocalidad0800.idprovincia=referenteprovincia0800.idprovincia")
		->join("left join", "sds_com_persona as referente", "referente.idpersona=referente0800.idpersona")
		->join("left join", "sds_com_configuracion as nacionalidad", "referente.nacionalidad=nacionalidad.idconfiguracion")
		->join("left join", "sds_com_configuracion as genero", "referente.genero=genero.idconfiguracion")
		->join("left join", "sds_com_configuracion as parentezco", "atencion.parentezco=parentezco.idconfiguracion")
		->join("left join", "sds_com_configuracion as documento_tipo", "referente.documento_tipo=documento_tipo.idconfiguracion")
		->join("left join", "mds_seg_usuario as usuario", "usuario.idusuario=atencion.idusuario")
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
						<h5> Detalle Atención: </h5>
					</th>
				</tr>
				<tr>
					<td><b>Fecha de Atención: </b><span><?= $atencion_datos['fechaatencion']; ?></span></td>
				</tr>
				<tr>
					<td>
						<b>Lugar de Intervención: </b><span><?= $atencion_datos['lugar_intervencion']; ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<b>Detalle del Lugar de Intervención: </b><span><?= $atencion_datos['lugar_especificacion']; ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<b>Defensora Interviniente: </b><span><?= $atencion_datos['defensora']; ?></span>
					</td>
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
					<td><b>Teléfono: </b><span><?= $atencion_datos['800telefono']; ?></span></td>
					<td></td>
					<td><b>Localidad: </b><span><?= $atencion_datos['800localidad']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
				<tr>
					<td colspan="3"><b>REFERENTE AFECTIVO </b></td>
				</tr>
				<tr>
					<td>
						<b>Documento: </b><span><?= $atencion_datos['referentedocumento']; ?></span>
					</td>
					<td></td>
					<td>
						<b>Nombre: </b><span><?= $atencion_datos['referentenombre']; ?>, <?= $atencion_datos['referenteapellido']; ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<b>Fecha de Nacimiento: </b><span><?= $atencion_datos['referentefechanacimiento']; ?></span>
					</td>
					<td></td>
					<td><b>Nacionalidad: </b><span><?= $atencion_datos['nacionalidad']; ?></span></td>
				</tr>
				<tr>
					<td><b>Género: </b><span><?= $atencion_datos['genero']; ?></span></td>
					<td></td>
					<td><b>Parentesco: </b><span><?= $atencion_datos['parentezco']; ?></span></td>
				</tr>
				<tr>
					<td><b>Localidad: </b><span><?= $atencion_datos['referente800localidad']; ?></span></td>
					<td></td>
					<td><b>Teléfono: </b><span><?= $atencion_datos['referente800telefono']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3"><b>Domicilio: </b><span><?= $atencion_datos['referente800domicilio']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3">
						<hr style="margin: 0">
					</td>
				</tr>
		</table>


		<p class="parrafo" style="text-align:justify"><b>Consideraciones / Plan de Acción: </b><span><?= $atencion_datos['plan_accion']; ?></span></p>

		<table>
			<?php if ($atencion_datos['archivo_adjunto']) : ?>
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