<?php
$filtro_rubro = '';
$idnota = $_GET['idnota'];

//POR AHORA HARDCODEAR LA LOCALIDAD NEUQUÉN (NO TENEMOS LOCALIDAD, SOLO LATITUD Y LONGITUD)
//NOTIFICADO A JP: 09/03/2020 15:07

/* Consulta SQL:
SELECT DATE_FORMAT(fecha,'%d') fecha_dia,
case 	WHEN DATE_FORMAT(fecha,'%m')=1 then 'Enero'
		WHEN DATE_FORMAT(fecha,'%m')=2 then 'Febrero'
		WHEN DATE_FORMAT(fecha,'%m')=3 then 'Marzo'
		WHEN DATE_FORMAT(fecha,'%m')=4 then 'Abril'
		WHEN DATE_FORMAT(fecha,'%m')=5 then 'Mayo'
		WHEN DATE_FORMAT(fecha,'%m')=6 then 'Junio'
		WHEN DATE_FORMAT(fecha,'%m')=7 then 'Julio'
		WHEN DATE_FORMAT(fecha,'%m')=8 then 'Agosto'
		WHEN DATE_FORMAT(fecha,'%m')=9 then 'Septiembre'								
		WHEN DATE_FORMAT(fecha,'%m')=10 then 'Octubre'
		WHEN DATE_FORMAT(fecha,'%m')=11 then 'Noviembre'
		WHEN DATE_FORMAT(fecha,'%m')=12 then 'Diciembre'						
END  fecha_mes,
DATE_FORMAT(fecha,'%Y') fecha_anio,CONCAT(DATE_FORMAT(fecha,'%Y'),'/',nota.numero) numero_nota,
destinatario_nombre,destinatario_cargo,destinatario_area,referencia,nota.detalle,
(SELECT descripcion FROM mds_org_organismo org WHERE org.idorganismo=nota.idorganismo) organismo,
expediente_guarismo,expediente_numero,expediente_anio,edificio.direccion,contacto.telefono,contacto.mail
FROM mds_not_nota nota
INNER JOIN mds_seg_usuario usuario ON usuario.idusuario=nota.idusuario
INNER JOIN mds_org_contacto contacto ON usuario.idcontacto=contacto.idcontacto
INNER JOIN mds_org_dispositivo dispositivo ON dispositivo.iddispositivo=contacto.iddispositivo
INNER JOIN sds_gis_capa_item edificio ON edificio.idcapaitem=dispositivo.idcapaitem
 */

$query = new yii\db\Query;
$query->select(["DATE_FORMAT(fecha,'%d') fecha_dia,
case 	WHEN DATE_FORMAT(fecha,'%m')=1 then 'Enero'
		WHEN DATE_FORMAT(fecha,'%m')=2 then 'Febrero'
		WHEN DATE_FORMAT(fecha,'%m')=3 then 'Marzo'
		WHEN DATE_FORMAT(fecha,'%m')=4 then 'Abril'
		WHEN DATE_FORMAT(fecha,'%m')=5 then 'Mayo'
		WHEN DATE_FORMAT(fecha,'%m')=6 then 'Junio'
		WHEN DATE_FORMAT(fecha,'%m')=7 then 'Julio'
		WHEN DATE_FORMAT(fecha,'%m')=8 then 'Agosto'
		WHEN DATE_FORMAT(fecha,'%m')=9 then 'Septiembre'
		WHEN DATE_FORMAT(fecha,'%m')=10 then 'Octubre'
		WHEN DATE_FORMAT(fecha,'%m')=11 then 'Noviembre'
		WHEN DATE_FORMAT(fecha,'%m')=12 then 'Diciembre'
END  fecha_mes,
DATE_FORMAT(fecha,'%Y') fecha_anio,CONCAT(nota.numero,'/',DATE_FORMAT(fecha,'%Y')) numero_nota,
destinatario_nombre,destinatario_cargo,destinatario_area,referencia,nota.detalle,
(SELECT descripcion FROM mds_org_organismo org WHERE org.idorganismo=nota.idorganismo) organismo,
expediente_guarismo,expediente_numero,expediente_anio,edificio.direccion,contacto.telefono,contacto.mail"])
	->from(["mds_not_nota nota"])
	->join("inner join", "mds_seg_usuario as usuario", "usuario.idusuario=nota.idusuario")
	->join("inner join", "mds_org_contacto as contacto", "usuario.idcontacto=contacto.idcontacto")
	->join("inner join", "mds_org_dispositivo as dispositivo", "dispositivo.iddispositivo=contacto.iddispositivo")
	->join("inner join", "sds_gis_capa_item as edificio", "edificio.idcapaitem=dispositivo.idcapaitem")
	->where(["idnota" => $idnota]);

$command = $query->createCommand();
$nota_datos = $command->queryOne();
$nombre = explode(' ', $nota_datos['destinatario_nombre'], 2)[0];
$sexo = substr($nombre, strlen($nombre) - 1, strlen($nombre)) == 'a' || $nombre=='Amancay';
?>
<html>
<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
		<div class="row" style="padding-top: 1%;">
			<div class="col-xs-offset-7 col-xs-5" style="text-align: right;">Neuquén, <?= $nota_datos['fecha_dia'] . ' de ' . $nota_datos['fecha_mes'] . ' de ' . $nota_datos['fecha_anio']; ?></div>
		</div>
		<div class="row" style="padding-top: 1%;padding-bottom: 5%">
			<div class="col-xs-offset-8 col-xs-4" style="text-align: right;">
				<b>Nota Nº <?= $nota_datos['numero_nota']; ?></b>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<span><?= ($sexo ? "A la " : "Al ") . $nota_datos['destinatario_cargo']; ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<span><?= ($sexo ? "Sra. " : "Sr. ") . $nota_datos['destinatario_nombre']; ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<span><?= $nota_datos['destinatario_area']; ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<span>S................/..............D</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-offset-5 col-xs-7" style="text-align: right;">
				<span>
					<?php
						$detalle=str_replace("\n","<br>",$nota_datos['detalle']);
						$numero_exp = $nota_datos['expediente_numero'];
						echo "<b>Ref: </b>" . $nota_datos['referencia'] .
						(($numero_exp!="") ? "<br><b>Exp: </b>" .
						$nota_datos['expediente_guarismo'] . "-" .
						$numero_exp . "/" .
						$nota_datos['expediente_anio']:""); ?>
				</span>
			</div>
		</div>
		<div class="row" style="padding-top: 5%;">
			<div class="col-xs-12" style="text-align: justify;">
				<p>
					<?= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$detalle; ?>
				</p>
			</div>
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
				<?= $nota_datos['direccion'] ?>
			</p>
		</div>
	</div>
</footer>

</html>