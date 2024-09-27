<?php

use app\controllers\SiteController;
use app\models\EdificioOficina;
use app\models\Empleado;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InfIps */
/* @var $form yii\widgets\ActiveForm */

$mysql_empleados = "SELECT e.idempleado, concat(p.apellido,' ',p.nombre) as email 
				FROM empleado e join personas p on e.idpersona = p.idpersona
				where e.activo = 1
				order by p.apellido, p.nombre";
$mysql_oficinas = "SELECT o.idoficina, concat(e.descripcion_fija, ' - ' ,o.descripcion) as descripcion
					from edificio_oficina o
					join edificio e on o.idedificio = e.idedificio
					where o.activo = 1
					order by e.descripcion_fija, o.descripcion";
?>

<div class="inf-ips-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-md-2">
			<?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>
		</div>

		<div class="col-md-10">
			<?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>
		</div>

	</div>
	<div class="row">
		<div class="col-md-6">
			<?= SiteController::actionGet_input_select2($form, $model, 'idoficina', 'cmb_oficina', EdificioOficina::findBySql($mysql_oficinas)->all(), 'idoficina', 'descripcion', 'Oficina', 'Seleccione Oficina...');
			$form->field($model, 'idempleado')->textInput(['maxlength' => true]) ?>

		</div>

		<div class="col-md-6">
			<?= SiteController::actionGet_input_select2($form, $model, 'idempleado', 'cmb_empleados', Empleado::findBySql($mysql_empleados)->all(), 'idempleado', 'email', 'Empleado (Opcional)', 'Seleccione Empleado...');
			$form->field($model, 'idempleado')->textInput(['maxlength' => true]) ?>
		</div>
	</div>







	<?php ActiveForm::end(); ?>

</div>