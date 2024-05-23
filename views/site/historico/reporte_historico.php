<?php

use app\models\Sds_his_registro_familia;
use kartik\grid\GridView;

$entregas = false;
$subsidios = false;

if(!isset($_POST['opciones']) || !isset($_POST['post-nombre-renaper'])) {
	echo "<script>window.close();</script>";
}else {
	foreach ($_POST['opciones'] as $opciones) {
		if ($opciones == 'entregas') {
			$entregas = true;
		}
		if ($opciones == 'subsidios') {
			$subsidios = true;
		}
	}
}

switch (date('m')) {
	case 1:
		$mes = 'Enero';
		break;
	case 2:
		$mes = 'Febrero';
		break;
	case 3:
		$mes = 'Marzo';
		break;
	case 4:
		$mes = 'Abril';
		break;
	case 5:
		$mes = 'Mayo';
		break;
	case 6:
		$mes = 'Junio';
		break;
	case 7:
		$mes = 'Julio';
		break;
	case 8:
		$mes = 'Agosto';
		break;
	case 9:
		$mes = 'Septiembre';
		break;
	case 10:
		$mes = 'Octubre';
		break;
	case 11:
		$mes = 'Noviembre';
		break;
	case 12:
		$mes = 'Diciembre';
		break;
}
?>
<html>
	<body>
		<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
			<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
			<div class="row" style="padding-top: 2%;">
				<div class="col-xs-offset-7 col-xs-5" style="text-align: right;">Neuquén, <?= date('d') . ' de ' . $mes . ' de ' . date('Y'); ?></div>
			</div>
			<br>
			<h3 style="text-align: center;">Reporte Histórico de <?= $entregas ? 'Entregas' : '' ?>
				<?= $entregas && $subsidios ? 'y' : '' ?>
				<?= $subsidios ? 'Subsidios' : '' ?></h3>
			<div class="row" style="padding-top: 5%;">
				<div class="panel panel-default" style="border: none; margin-bottom: 5px;">
					<div class="panel-heading" style="background:#0088cc; padding:5px;">
						<h4 style="margin: 0; padding:0; color:#fff;">
							<b>DATOS RENAPER</b>
						</h4>
					</div>
					<div class="panel-body" style="border: 1px solid rgba(100, 50, 50, 0.3); border-top: none;">
						<div class="" id="datos-renaper">
							<div id="txt_mensaje"></div>
							<div id="load-animated" class="load-animated"></div>
							<ul class="col-xs-8" id="list-data-renaper">
								<li id="nombre-renaper">Nombres: <b><?= $_POST['post-nombre-renaper'] ?></b></li>
								<li id="apellido-renaper">Apellido: <b><?= $_POST['post-apellido-renaper'] ?></b></li>
								<li id="cuil-renaper">CUIL: <b><?= $_POST['post-cuil-renaper'] ?></b></li>
								<li id="fecha_nacimiento-renaper"> Fecha de nacimiento: <b><?= $_POST['post-fnacimiento-renaper'] ?></b></li>
								<li id="domicilio-renaper">Domicilio: <b><?= $_POST['post-domicilio-renaper'] ?></b></li>
								<li id="localidad-renaper">Localidad: <b><?= $_POST['post-localidad-renaper'] ?></b></li>
								<li id="nacionalidad-renaper">Nacionalidad: <b><?= $_POST['post-nacionalidad-renaper'] ?></b></li>
								<?php $legajo = Sds_his_registro_familia::find()->where('dni='.$dni)->one();?>
								<li>Legajo Registro de Familia: <b><?=(isset($legajo->legajo) ? ' <b>'.$legajo->legajo.'</b>' : 'S/D')?></b></li>
							</ul>
							<div>
								<img id="img-renaper" src="<?= $_POST['post-img-renaper'] ?>" alt="" height="150px" />
							</div>
						</div>
					</div>
				</div>
				<br>
				<?php if ($entregas) : ?>
					<?= $subsidios ? '<h4><b>Entregas</b></h4>' : '' ?>
					<!-- Grid Entregas -->
					<?= GridView::widget([
						'id' => 'crud-datatable-entrega',
						'dataProvider' => $dataProviderEntrega,
						'columns' => require(Yii::$app->basePath . '/views/sds_his_entrega/_columns.php')
					]) ?>
					<hr>
					<!-- Fin Grid Entregas -->
				<?php endif; ?>
				<?php if ($subsidios) : ?>
					<?= $entregas ? '<h4><b>Subsidios</b></h4>' : '' ?>
					<!-- Grid Subsidios -->
					<?= GridView::widget([
						'id' => 'crud-datatable-subsidio',
						'dataProvider' => $dataProviderSubsidio,
						'columns' => require(Yii::$app->basePath . '/views/sds_his_admix/_columns.php')
					]) ?>
					<!-- Fin Grid Subsidios -->
					<hr>
				<?php endif; ?>
			</div>
		</div>
	</body>
	<footer style="position: fixed; left: 0;
	  bottom: 0px;
	  width: 100%;">
		<div class="row">
			<div class="col-xs-12" style="text-align: center;">
				<p> Gdor. Anaya 299-399, Q8300 Neuquén, Argentina </p>
			</div>
		</div>
	</footer>
</html>