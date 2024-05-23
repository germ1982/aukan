<?php

use app\models\Sds_gis_capa;
use app\models\Sds_gis_capa_item;

$filtro_rubro = '';
$idcapa = $_GET['idcapa'];
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
	"descripcion", "detalle", "estado", "direccion"
])->from(["sds_gis_capa_item"])
	->where("activo and idcapa=$idcapa");

$command = $query->createCommand();
$capa_items = $command->queryAll();

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<h4>Ubicaciones de Capa <?= Sds_gis_capa::findOne($idcapa)->descripcion ?></h4>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Detalle</th>
					<th>Dirección</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($capa_items as $capa_item) :
				?>
					<tr>
						<td style="padding:3px;"><?= $capa_item['descripcion']; ?></td>
						<td style="padding:3px;"><?= $capa_item['detalle']; ?></td>
						<td style="padding:3px;"><?= $capa_item['direccion']; ?></td>
						<td style="padding:3px;text-align:center;">
							<?php
							$img_url = 'img/circle_green.png';
							switch ($capa_item['estado']) {
								case Sds_gis_capa_item::ESTADO_VERDE:
									$img_url = 'img/circle_green.png';
									break;
								case Sds_gis_capa_item::ESTADO_AMARILLO:
									$img_url = 'img/circle_warn.png';
									break;
								case Sds_gis_capa_item::ESTADO_ROJO:
									$img_url = 'img/circle_red.png';
									break;
							}
							echo '<img src="' . $img_url . '"  height="16px">';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</body>

</html>