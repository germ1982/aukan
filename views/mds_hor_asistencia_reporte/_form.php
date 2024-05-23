<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_asistencia_reporte */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-hor-asistencia-reporte-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'periodo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'idfranco')->textInput() ?>

    <?= $form->field($model, 'idregistrohorario')->textInput() ?>

    <?= $form->field($model, 'idlicencia')->textInput() ?>

    <?= $form->field($model, 'codContacto')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
