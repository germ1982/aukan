<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_gis_capa;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_plantilla */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-r-plantilla-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_plantilla')->textInput()->label("Nombre de plantilla") ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear pepot' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
