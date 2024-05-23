<?php

use app\models\Sds_800_llamada;
use yii\helpers\Url;

$idllamada = $_GET['idllamada'];

$llamada_datos = $llamadaDatos;
$atencion_datos = $atencionDatos;



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

		<!-- DATOS DEL LLAMANTE: -->
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
				<td><b>Profesional Interviniente: </b><span><?= $llamada_datos['nombre_profesional'] ?></span></td>
				<td></td>
			</tr>
		</table>

		<!-- DETALLE DE LA SITUACIÓN: -->
		<table>
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
						<b>Nombre: </b><span><?= $atencion_datos['personaapellido']; ?>, <?= $atencion_datos['personanombre']; ?></span>
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
						<b>Nombre: </b><span><?= $atencion_datos['referenteapellido']; ?>, <?= $atencion_datos['referentenombre']; ?></span>
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
		<table>
			<tr>
				<td colspan="3"><b>DATOS DE NIÑO, NIÑA Y/O ADOLESCENTE INSTITUCIONALIZADO </b></td>
			</tr>
			<tr>
				<td colspan="3"><b> Último lugar en el que estuvo alojado: </b><span><?= $atencion_datos['alojado']; ?></span></td>
			</tr>
			<tr>
				<td colspan="3"><b>Hogar de referencia: </b><span><?= $atencion_datos['hogar']; ?></span></td>
			</tr>
			<tr>
				<td colspan="3"><b>Día y hora de la salida sin autorización: </b><span><?= $atencion_datos['fechasalida']; ?></span></td>
			</tr>
			<tr>
				<td colspan="3"><b>Operador de turno: </b><span><?= $atencion_datos['operador']; ?></span></td>
			</tr>
			<tr>
				<td colspan="3"><b>Equipo técnico y profesionales del hogar: </b><span><?= $atencion_datos['equipo_tecnico']; ?></span></td>
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
				<td><b>Edad que dice tener: </b><span><?= $atencion_datos['edad']; ?></span></td>
				<td></td>
				<td><b>¿Sabe leer? </b><span><?= $atencion_datos['sabe_leer']; ?></span></td>
			</tr>
			<tr>
				<td><b>Máximo nivel de estudio alcanzado: </b><span><?= $atencion_datos['nivel_estudio']; ?></span></td>
				<td></td>
				<?php if ($atencion_datos['nivel_estudio'] != 'Sin Datos') : ?>
					<td><b>Nombre del establecimiento educativo: </b><span><?= $atencion_datos['establecimiento']; ?></span></td>
				<?php endif; ?>
			</tr>
			<tr>
				<td><b>¿Efectúa algún tipo de trabajo? </b><span><?= $atencion_datos['trabaja']; ?></span></td>
				<td></td>
				<td>
					<?php if ($atencion_datos['trabaja'] == 'Si') : ?>
						<b>¿Qué tipo de trabajo? </b><span><?= $atencion_datos['tipo_trabajo']; ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3"><b>¿Estuvo atendiendo algún equipo Técnico al grupo Familiar en este tiempo? </b><span><?= $atencion_datos['atendido']; ?></span></td>
			</tr>
			<?php if ($atencion_datos['atendido'] == 'Si') : ?>
				<tr>
					<td><b>¿De qué institución? </b><span><?= $atencion_datos['institucion']; ?></span></td>
					<td></td>
					<td><b>Nombre de el o los profesionales: </b><span><?= $atencion_datos['nombre_profesionales']; ?></span></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td colspan="3"><b>¿Posee el grupo conviviente del NNy/oA algún beneficio Social? </b><span><?= $atencion_datos['beneficio_social']; ?></span></td>
			</tr>
			<?php if ($atencion_datos['beneficio_social'] == 'Si') : ?>
				<tr>
					<td colspan="3"><b>Área que otorga dicho beneficio: </b><span><?= $atencion_datos['area_beneficio']; ?></span></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td colspan="3"><b>¿Concurre el NNy/oA a algún centro de salud? </b><span><?= $atencion_datos['centro_salud']; ?></span></td>
			</tr>
			<?php if ($atencion_datos['centro_salud'] == 'Si') : ?>
				<tr>
					<td colspan="3"><b>Profesional y/o Institución: </b><span><?= $atencion_datos['nombre_centro_salud']; ?></span></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td><b>¿Posee obra social? </b><span><?= $atencion_datos['obra_social']; ?></span></td>
				<td></td>
				<td>
					<?php if ($atencion_datos['obra_social'] == 'Si') : ?>
						<b>¿Cuál? </b><span><?= $atencion_datos['nombre_obra_social']; ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3"><b>¿Se encuentra bajo tratamiento médico/psicológico y/o psiquiátrico? </b><span><?= $atencion_datos['tratamiento_medico']; ?></span></td>
			</tr>
			<?php if ($atencion_datos['tratamiento_medico'] == 'Si') : ?>
				<tr>
					<td colspan="3"><b>Profesional y/o Institución: </b><span><?= $atencion_datos['tratamiento_institucion']; ?></span></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td colspan="3"><b>¿Se encuentra orientado en tiempo y espacio? </b><span><?= $atencion_datos['orientado']; ?></span></td>
			</tr>
			<tr>
				<td><b>¿Se encuentra Intoxicado? </b><span><?= $atencion_datos['intoxicado']; ?></span></td>
				<td></td>
				<td><b>¿Se encuentra violentado? </b><span><?= $atencion_datos['violentado']; ?></span></td>
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