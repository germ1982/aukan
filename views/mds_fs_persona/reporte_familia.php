<?php

use app\models\Mds_fs_persona;
use yii\helpers\Url;

$idfspersona = $_GET['idfspersona'];

$query = new yii\db\Query;
$query->select(["persona.*","l.descripcion as localidad","p.descripcion as provincia","n.descripcion as nacionalidad","g.descripcion as genero","e.descripcion as estado_civil","ne.descripcion as nivel_escolaridad"])
	->from(["mds_fs_persona persona"])
	->join("inner join", "sds_com_localidad as l", "persona.idlocalidad=l.idlocalidad")
	->join("inner join", "sds_com_provincia as p", "persona.idprovincia=p.idprovincia")
	->join("left join", "sds_com_configuracion as n", "persona.nacionalidad=n.idconfiguracion")
	->join("left join", "sds_com_configuracion as g", "persona.genero=g.idconfiguracion")
	->join("left join", "sds_com_configuracion as e", "persona.estado_civil=e.idconfiguracion")
	->join("left join", "sds_com_configuracion as ne", "persona.nivel_escolaridad=ne.idconfiguracion")
	->where(["persona.idfspersona" => $idfspersona]);

$command = $query->createCommand();
$persona_datos = $command->queryOne();
?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE FAMILIA SOLIDARIA</h4>
			<hr style="margin: 0">
		</div>
		<table>
			<tr>
				<td>
					<b>Nro. de Inscripción: </b><span><?= $idfspersona ?></span>
				</td>
				<td></td>
				<td>
					<b>Estado: </b><span><?= Mds_fs_persona::getEstado($persona_datos['estado']) ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Fecha de llamada: </b><span><?= Mds_fs_persona::getFecha($persona_datos['fecha_nacimiento']) ?></span>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<th><br></th>
			</tr>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5>Información Personal: </h5>
				</th>
			</tr>
			<tr>
				<td>
					<b>Documento: </b><span><?= $persona_datos['dni']; ?></span>
				</td>
				<td></td>
				<td>
					<b>Nombre: </b><span><?= $persona_datos['nombre']; ?> <?= $persona_datos['apellido']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Fecha de Nacimiento: </b><span><?= Mds_fs_persona::getFecha($persona_datos['fecha_nacimiento']) ?></span>
				</td>
				<td></td>
				<td><b>Lugar de Nacimiento: </b><span><?= $persona_datos['lugar_nacimiento']; ?></span></td>
			</tr>
			<tr>
				<td><b>Nacionalidad: </b><span><?= $persona_datos['nacionalidad']; ?></span></td>
				<td></td>
				<td><b>Género: </b><span><?= $persona_datos['genero']; ?></span></td>
			</tr>
			<tr>
				<td><b>Estado Civil: </b><span><?= $persona_datos['estado_civil']; ?></span></td>
				<td></td>
				<td><b>Domicilio: </b><span><?= $persona_datos['domicilio']; ?></span></td>
			</tr>
			<tr>
				<td><b>Localidad: </b><span><?= $persona_datos['localidad']; ?></span></td>
				<td></td>
				<td><b>Provincia: </b><span><?= $persona_datos['provincia']; ?></span></td>
			</tr>
			<tr>
				<td><b>Tiempo de Residencia en la Provincia: </b><span><?= $persona_datos['tiempo_provincia']; ?></span></td>
				<td></td>
				<td><b>Nivel de escolaridad Alcanzado: </b><span><?= $persona_datos['nivel_escolaridad']; ?></span></td>
			</tr>
			<tr>
				<td><b>Profesión: </b><span><?= $persona_datos['profesion']; ?></span></td>
				<td></td>
				<td><b>Teléfono: </b><span><?= $persona_datos['telefono']; ?></span></td>
			</tr>
			<tr>
				<td><b>Teléfono Adicional: </b><span><?= $persona_datos['telefono_alternativo']; ?></span></td>
				<td></td>
				<td><b>Email: </b><span><?= $persona_datos['mail']; ?></span></td>
			</tr>
		</table>
		<p class="parrafo" style="text-align:justify"><b>Grupo Familiar </b><br><span><?= $persona_datos['grupo_familiar']; ?></span></p>
		<table>
			<tr>
				<th><br></th>
			</tr>
			<tr style="background-color: #dddddd;">
				<th class="titulo">
					<h5> Información General: </h5>
				</th>
			</tr>
		</table>
		<p class="parrafo" style="text-align:justify"><b>¿Se encuentra inscripto/a en el Registro Único de Adopción (RUA)? </b><span><?= $persona_datos['inscripto_rua_check'] == 1 ? 'Si' : 'No'; ?></span></p>
		<?php if($persona_datos['inscripto_rua_check'] == 0) : ?>
			<p class="parrafo" style="text-align:justify"><b>¿Alguna vez tuvo intenciones de hacerlo? </b><br/><span><?= $persona_datos['inscripto_rua']; ?></span></p>
		<?php endif; ?>
		<p class="parrafo" style="text-align:justify"><b>Describa brevemente el motivo por el cual desea ser una familia solidaria</b><br/><span><?= $persona_datos['motivo_fs']; ?></span></p>
		<p class="parrafo" style="text-align:justify"><b>¿Hay acuerdo entre todos/as los/as miembros de su grupo conveniente para postularse como FS? ¿Quién lo propuso? </b><br/><span><?= $persona_datos['acuerdo_familia']; ?></span></p>
		<p class="parrafo" style="text-align:justify"><b>¿Cómo tomo conocimiento de la existencia del programa? </b><br/><span><?= $persona_datos['conocimiento_programa']; ?></span></p>
		<p class="parrafo" style="text-align:justify"><b>¿Qué disponibilidad horaria tiene para entrevistas? </b><br/><span><?= $persona_datos['disponibilidad_horaria']; ?></span></p>
		<p class="parrafo" style="text-align:justify"><b>Franja de edades preferentes </b><br/><span><?= $persona_datos['franja_etaria']; ?></span></p>
		<p class="parrafo" style="text-align:justify"><b>Consulta o duda </b><br/><span><?= $persona_datos['consulta']; ?></span></p>
	</div>
</body>