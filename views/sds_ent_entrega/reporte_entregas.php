<?php
$filtro_rubro = '';
$identregas = $_GET['identregas'];
$tienefotofrente = true;
$tienefotodorso = true;
//POR AHORA HARDCODEAR LA LOCALIDAD NEUQUÉN (NO TENEMOS LOCALIDAD, SOLO LATITUD Y LONGITUD)
//NOTIFICADO A JP: 09/03/2020 15:07

/* Consulta SQL:
select identrega,cantidad,(select descripcion
from sds_ent_tipo tipo
where tipo.idtipo=sds_ent_entrega.idtipo),dni,observaciones
from sds_ent_entrega
 */

$query = new yii\db\Query;
$query->select([
	"DATE_FORMAT(curdate(),'%d') fecha_dia",
	"case 	WHEN DATE_FORMAT(curdate(),'%m')=1 then 'Enero'
		WHEN DATE_FORMAT(curdate(),'%m')=2 then 'Febrero'
		WHEN DATE_FORMAT(curdate(),'%m')=3 then 'Marzo'
		WHEN DATE_FORMAT(curdate(),'%m')=4 then 'Abril'
		WHEN DATE_FORMAT(curdate(),'%m')=5 then 'Mayo'
		WHEN DATE_FORMAT(curdate(),'%m')=6 then 'Junio'
		WHEN DATE_FORMAT(curdate(),'%m')=7 then 'Julio'
		WHEN DATE_FORMAT(curdate(),'%m')=8 then 'Agosto'
		WHEN DATE_FORMAT(curdate(),'%m')=9 then 'Septiembre'
		WHEN DATE_FORMAT(curdate(),'%m')=10 then 'Octubre'
		WHEN DATE_FORMAT(curdate(),'%m')=11 then 'Noviembre'
		WHEN DATE_FORMAT(curdate(),'%m')=12 then 'Diciembre'
END  fecha_mes",
	"DATE_FORMAT(curdate(),'%Y') fecha_anio", "cantidad", "dni", "observaciones", "fecha_hora",
	"(select descripcion from sds_com_configuracion conf,sds_ent_entrega emisor 
					where emisor.identrega=sds_ent_entrega.emisor and conf.idconfiguracion=emisor.receptor) nombre_emisor",
	"(select concat(apellido,', ',nombre) from sds_com_persona p where p.idpersona=sds_ent_entrega.idpersona) nomap"
])
	->from(["sds_ent_entrega"])
	->where("dni is not null and emisor in ($identregas)");

$command = $query->createCommand();
$entregas_datos = $command->queryAll();

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<h4><?= $_GET['titulo']; ?></h4>
		<table class="table" frame=hsides rules=rows>
			<?php
			foreach ($entregas_datos as $entrega_datos) :
				$fecha_carga = $entrega_datos['fecha_hora'];
				$unafecha = explode(" ", $fecha_carga);
				$unafecha = explode("-", $unafecha[0]);
				$fecha_carga = $unafecha[2] . "/" . $unafecha[1] . "/" . $unafecha[0];
			?>
				<tr>
					<td style="padding-top:10px;"><b>Emisor: </b></td>
					<td style="padding-top:10px;padding-right:5px;"> <?= $entrega_datos['nombre_emisor']; ?> </td>
					<td style="padding-top:10px;"><b>Fecha: </b></td>
					<td style="padding-top:10px;padding-right:5px;"><?= $fecha_carga; ?> </td>
				</tr>
				<tr>
					<td><b>DNI: </b></td>
					<td style="padding-right:5px;"> <?= $entrega_datos['dni']; ?> </td>
					<td><b>Apellido y Nombre: </b></td>
					<td style="padding-right:5px;"> <?= $entrega_datos['nomap']; ?> </td>
					<td><b>Cantidad: </b></td>
					<td style="padding-right:5px;"> <?= $entrega_datos['cantidad']; ?> </td>
				</tr>
				<tr>
					<td style="border-bottom: 1px solid #cdcdcd;padding-bottom:10px;"><b>Observaciones: </b></td>
					<td colspan="3" style="border-bottom: 1px solid #cdcdcd;padding-bottom:10px;"> <?= $entrega_datos['observaciones']; ?> </td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</body>

</html>