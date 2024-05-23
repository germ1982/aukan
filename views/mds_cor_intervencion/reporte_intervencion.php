<?php

use app\models\Mds_cor_intervencion_articulacion;
use app\models\Mds_cor_intervencion_consumo;
use app\models\Mds_cor_intervencion_problema;
use app\models\Sds_com_persona;

$idintervencion = $_GET['idintervencion'];

$query = new yii\db\Query;
$query->select(["DATE_FORMAT(fecha_informe,'%d') fecha_informe_dia,
case 	WHEN DATE_FORMAT(fecha_informe,'%m')=1 then 'Enero'
		WHEN DATE_FORMAT(fecha_informe,'%m')=2 then 'Febrero'
		WHEN DATE_FORMAT(fecha_informe,'%m')=3 then 'Marzo'
		WHEN DATE_FORMAT(fecha_informe,'%m')=4 then 'Abril'
		WHEN DATE_FORMAT(fecha_informe,'%m')=5 then 'Mayo'
		WHEN DATE_FORMAT(fecha_informe,'%m')=6 then 'Junio'
		WHEN DATE_FORMAT(fecha_informe,'%m')=7 then 'Julio'
		WHEN DATE_FORMAT(fecha_informe,'%m')=8 then 'Agosto'
		WHEN DATE_FORMAT(fecha_informe,'%m')=9 then 'Septiembre'
		WHEN DATE_FORMAT(fecha_informe,'%m')=10 then 'Octubre'
		WHEN DATE_FORMAT(fecha_informe,'%m')=11 then 'Noviembre'
		WHEN DATE_FORMAT(fecha_informe,'%m')=12 then 'Diciembre'
END  fecha_informe_mes,
DATE_FORMAT(fecha_informe,'%Y') fecha_informe_anio,CONCAT(idintervencion,'/',DATE_FORMAT(fecha_informe,'%Y')) numero_intervencion,
derivaciones_previas,inte.detalle,intervenciones,derivaciones,inte.idpersona, referente_dni,
referente_vinculo, referente_telefono,referente_nombre,idintervencion,persona.nombre,
persona.apellido, persona.documento, persona.fecha_nacimiento, genero.descripcion as genero, 
provincia.descripcion as provincia, localidad.descripcion as localidad,
prof.nombre as nom_profesional, prof.apellido as apre_profesional,
tiempoResidenciaNqn.descripcion as tiemporesidencianqn, inte.plan_accion,
denuncia.descripcion as denuncia, inte.nombre_autopercibido,
confi.descripcion,edificio.direccion,contacto.telefono,contacto.mail, ley.descripcion as ley"])
	->from(["mds_cor_intervencion inte"])
	->join("inner join", "sds_com_persona as persona", "persona.idpersona= inte.idpersona")
	->join("inner join", "mds_org_contacto as us", "us.idcontacto=inte.profesional")
	->join("inner join", "sds_com_persona as prof", "prof.idpersona=us.idpersona")
	->join("inner join", "sds_com_configuracion as confi", "confi.idconfiguracion= inte.tipo")
	->join("left join", "sds_com_configuracion as ley", "ley.idconfiguracion= inte.ley")
	->join("inner join", "mds_seg_usuario as usuario", "usuario.idusuario=inte.idusuario")
	->join("inner join", "mds_org_contacto as contacto", "usuario.idcontacto=contacto.idcontacto")
	->join("inner join", "mds_org_dispositivo as dispositivo", "dispositivo.iddispositivo=contacto.iddispositivo")
	->join("inner join", "sds_gis_capa_item as edificio", "edificio.idcapaitem=dispositivo.idcapaitem")
	->join("inner join", "sds_com_configuracion as genero", "genero.idconfiguracion=persona.genero")
	->join("left join", "sds_com_localidad as localidad", "localidad.idlocalidad=inte.idlocalidad")
	->join("left join", "sds_com_provincia as provincia", "provincia.idprovincia=localidad.idprovincia")
	->join("left join", "sds_com_configuracion as tiempoResidenciaNqn", "tiempoResidenciaNqn.idconfiguracion=inte.idtiemporesidencianqn")
	->join("left join", "sds_com_configuracion as denuncia", "denuncia.idconfiguracion=inte.iddenuncia")
	->where(["idintervencion" => $idintervencion]);

/**SELECT DATE_FORMAT(fecha_informe,'%d') fecha_informe_dia,
case 	WHEN DATE_FORMAT(fecha_informe,'%m')=1 then 'Enero'
		WHEN DATE_FORMAT(fecha_informe,'%m')=2 then 'Febrero'
		WHEN DATE_FORMAT(fecha_informe,'%m')=3 then 'Marzo'
		WHEN DATE_FORMAT(fecha_informe,'%m')=4 then 'Abril'
		WHEN DATE_FORMAT(fecha_informe,'%m')=5 then 'Mayo'
		WHEN DATE_FORMAT(fecha_informe,'%m')=6 then 'Junio'
		WHEN DATE_FORMAT(fecha_informe,'%m')=7 then 'Julio'
		WHEN DATE_FORMAT(fecha_informe,'%m')=8 then 'Agosto'
		WHEN DATE_FORMAT(fecha_informe,'%m')=9 then 'Septiembre'
		WHEN DATE_FORMAT(fecha_informe,'%m')=10 then 'Octubre'
		WHEN DATE_FORMAT(fecha_informe,'%m')=11 then 'Noviembre'
		WHEN DATE_FORMAT(fecha_informe,'%m')=12 then 'Diciembre'
END  fecha_informe_mes, DATE_FORMAT(fecha_informe,'%Y') fecha_informe_anio,CONCAT(idintervencion,'/',DATE_FORMAT(fecha_informe,'%Y')) numero_intervencion,
derivaciones_previas,inte.detalle,intervenciones,derivaciones,inte.idpersona, referente_dni,
referente_vinculo, referente_telefono,referente_nombre,idintervencion,persona.nombre,
 persona.apellido, persona.documento, prof.nombre as nom_profesional, prof.apellido as apre_profesional,
 confi.descripcion,edificio.direccion,contacto.telefono,contacto.mail, ley.descripcion as ley
  from mds_cor_intervencion as inte
inner join sds_com_persona as persona  on persona.idpersona= inte.idpersona
inner join mds_org_contacto as us on us.idcontacto=inte.profesional
INNER JOIN sds_com_persona as prof ON prof.idpersona=us.idpersona
INNER JOIN  sds_com_configuracion as confi on confi.idconfiguracion= inte.tipo
left join sds_com_configuracion as ley on ley.idconfiguracion= inte.ley
inner join mds_seg_usuario as usuario on usuario.idusuario=inte.idusuario
inner join mds_org_contacto as contacto on usuario.idcontacto=contacto.idcontacto
inner join mds_org_dispositivo as dispositivo on dispositivo.iddispositivo=contacto.iddispositivo
inner join sds_gis_capa_item as edificio on edificio.idcapaitem=dispositivo.idcapaitem
WHERE idintervencion = 6*/
$command = $query->createCommand();
$intervencion_datos = $command->queryOne();
$intervencion_datos['edad'] = Sds_com_persona::getEdad($intervencion_datos['fecha_nacimiento']);

$consumosString = '';
$problemasString = '';
$articulacionesString = '';
$consumos = Mds_cor_intervencion_consumo::getConsumosCargadosByIdIntervencion($intervencion_datos['idintervencion']);
$problemas = Mds_cor_intervencion_problema::getProblemasCargadosByIdIntervencion($intervencion_datos['idintervencion']);
$articulaciones = Mds_cor_intervencion_articulacion::getArticulacionesCargadasByIdIntervencion($intervencion_datos['idintervencion']);
if (count($consumos) > 0) {
	foreach ($consumos as $key => $consumo) {
		$consumosString .=  $key + 1 === count($consumos) ? "{$consumo['descripcion']}" : "{$consumo['descripcion']}, ";
	}
}

if (count($problemas) > 0) {
	foreach ($problemas as $key => $problema) {
		$problemasString .=  $key + 1 === count($problemas) ? "{$problema['descripcion']}" : "{$problema['descripcion']}, ";
	}
}

if (count($articulaciones) > 0) {
	foreach ($articulaciones as $key => $articulacion) {
		$articulacionesString .=  $key + 1 === count($articulaciones) ? "{$articulacion['descripcion']}" : "{$articulacion['descripcion']}, ";
	}
}
?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
		<div class="row" style="padding-top: 1%;">
			<div class="col-xs-5" style="text-align: left;"><b> Fecha Informe: </b> <?= $intervencion_datos['fecha_informe_dia'] . ' de ' . $intervencion_datos['fecha_informe_mes'] . ' de ' . $intervencion_datos['fecha_informe_anio']; ?></div>
		</div>

		<div class="row" style="padding-top: 1%;padding-bottom: 2%">
			<div class="col-xs-12" style="text-align: center;"><b> DATOS DE LA PERSONA </b> </div>
			<div class="col-xs-12" style="padding-top: 1%; text-align: left;"><b> Documento: </b> <?= $intervencion_datos['documento']; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left;"><b> Nombre y Apellido: </b> <?= mb_strtoupper($intervencion_datos['nombre']) . '  ' . mb_strtoupper($intervencion_datos['apellido']); ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left;"><b> Fecha Nacimiento: </b> <?= date('d/m/Y', strtotime($intervencion_datos['fecha_nacimiento'])); ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left;"><b> Edad: </b> <?= $intervencion_datos['edad']; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left;"><b> Género: </b> <?= $intervencion_datos['genero']; ?></div>
		</div>
		<div class="row" style="padding-top: 1%;padding-bottom: 2%">
			<div class="col-xs-12" style="text-align: center;"><b> DATOS DE LA INTERVENCIÓN </b> </div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $intervencion_datos['nombre_autopercibido'] ? "block" : "none" ?>"><b>Nombre autopercibido: </b> <?= $intervencion_datos['nombre_autopercibido'] ? mb_strtoupper($intervencion_datos['nombre_autopercibido']) : '' ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left;"><b>Profesional Interviniente: </b> <?= mb_strtoupper($intervencion_datos['nom_profesional']) . '  ' . mb_strtoupper($intervencion_datos['apre_profesional']); ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left;"> <b>Tipo de Intervención: </b> <?= $intervencion_datos['descripcion']; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $intervencion_datos['ley'] ? "block" : "none" ?>"> <b>En el marco de: </b> <?= $intervencion_datos['ley']; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $intervencion_datos['provincia'] ? "block" : "none" ?>"> <b>Origen Provincia: </b> <?= $intervencion_datos['provincia']; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $intervencion_datos['localidad'] ? "block" : "none" ?>"> <b>Origen Localidad: </b> <?= $intervencion_datos['localidad']; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $intervencion_datos['tiemporesidencianqn'] ? "block" : "none" ?>"> <b>Tiempo de residencia en Neuquén: </b> <?= $intervencion_datos['tiemporesidencianqn']; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $intervencion_datos['denuncia'] ? "block" : "none" ?>"> <b>Denuncia: </b> <?= $intervencion_datos['denuncia']; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $consumosString ? "block" : "none" ?>"> <b>Consumos: </b> <?= $consumosString; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $problemasString ? "block" : "none" ?>"> <b>Problemas: </b> <?= $problemasString; ?></div>
			<div class="col-xs-12" style="padding-top: 1%;text-align: left; display:<?= $articulacionesString ? "block" : "none" ?>"> <b>Articulación interinstitucional: </b> <?= $articulacionesString; ?></div>

			<div class="col-xs-12" style="padding-top: 1%; text-align: justify; display:<?= $intervencion_datos['derivaciones_previas'] ? "block" : "none" ?> ">
				<p>
					<span><b> Derivaciones Previas: </b></span>
					<?= "&nbsp;&nbsp;" . $intervencion_datos['derivaciones_previas']; ?>
				</p>
			</div>
			<div class="col-xs-12" style="padding-top: 1%; text-align: justify; display:<?= $intervencion_datos['plan_accion'] ? "block" : "none" ?>">
				<p>
					<span><b> Plan de acción: </b></span>
					<?= "&nbsp;&nbsp;" . $intervencion_datos['plan_accion']; ?>
				</p>
			</div>
			<div class="col-xs-12" style="padding-top: 1%; text-align: justify; display:<?= $intervencion_datos['detalle'] ? "block" : "none" ?>">
				<p>
					<span><b> Detalle: </b></span>
					<?= "&nbsp;&nbsp;" . $intervencion_datos['detalle']; ?>
				</p>
			</div>
			<div class="col-xs-12" style="padding-top: 1%; text-align: justify;  display:<?= $intervencion_datos['intervenciones'] ? "block" : "none" ?>">
				<p>
					<span><b> Intervenciones Realizadas: </b></span>
					<?= "&nbsp;&nbsp;" . $intervencion_datos['intervenciones']; ?>
				</p>
			</div>
			<div class="col-xs-12" style="padding-top: 1%; text-align: justify;  display:<?= $intervencion_datos['derivaciones'] ? "block" : "none" ?>">
				<p>
					<span><b> Derivaciones Futuras: </b></span>
					<?= "&nbsp;&nbsp;" . $intervencion_datos['derivaciones']; ?>
				</p>
			</div>
		</div>
		<div class="row" style="padding-top: 1%;padding-bottom: 3%">
			<div class="col-xs-12" style="text-align: center; display:
			<?= $intervencion_datos['referente_dni']  || $intervencion_datos['referente_nombre'] ||
				$intervencion_datos['referente_vinculo']  || 	$intervencion_datos['referente_telefono']
				? "block" : "none" ?> "><b> TERCERO REFERENTE </b> </div>
			<div class="col-xs-12" style="text-align: left; display:<?= $intervencion_datos['referente_dni'] ? "block" : "none" ?>"> <i> Documento del Responsable: </i><?= $intervencion_datos['referente_dni']; ?></div>
			<div class="col-xs-12" style="text-align: left; display:<?= $intervencion_datos['referente_nombre'] ? "block" : "none" ?>"> <i> Nombre del Respondable: </i><?= mb_strtoupper($intervencion_datos['referente_nombre']); ?></div>
			<div class="col-xs-12" style="text-align: left; display:<?= $intervencion_datos['referente_vinculo'] ? "block" : "none" ?>"> <i> Vínculo del Responsable: </i><?= $intervencion_datos['referente_vinculo']; ?></div>
			<div class="col-xs-12" style="text-align: left; display:<?= $intervencion_datos['referente_telefono'] ? "block" : "none" ?>"> <i> Teléfono del Responsable: </i><?= $intervencion_datos['referente_telefono']; ?></div>
		</div>
	</div>
</body>

<footer style="position: fixed; left: 0;
  bottom: 0px;
  width: 100%;">
	<div class="row">
		<div class="col-xs-12" style="text-align: center;">
			<p>
				<!--str_replace(', Q8300 Neuquén, Argentina','',$nota_datos['direccion'])-->
				<!--?= $intervencion_datos['direccion'] ?-->
				________________________________________________________________________________________________________
				Ministerio de Desarrollo Social y Trabajo - Neuquén
			</p>
		</div>
	</div>
</footer>

</html>