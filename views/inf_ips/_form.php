<?php

use app\controllers\SiteController;
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
?>

<div class="inf-ips-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= SiteController::actionGet_input_select2($form, $model, 'idempleado','cmb_empleados',Empleado::findBySql($mysql_empleados)->all(),'idempleado','email','Empleado','Seleccione Empleado...');
	$form->field($model, 'idempleado')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
