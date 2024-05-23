<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-persona-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

   
    <?= $form->field($model, 'hijos')->textInput() ?>

    <?= $form->field($model, 'tienecuil')->textInput() ?>

    <?= $form->field($model, 'precuil')->textInput() ?>

    <?= $form->field($model, 'postcuil')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telfijo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telcel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idestado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iddomicilio')->textInput() ?>

    

    <?= $form->field($model, 'idestadocivil')->textInput() ?>

    <?= $form->field($model, 'iddocadicional')->textInput() ?>

    <?= $form->field($model, 'fechaalta')->textInput() ?>

    <?= $form->field($model, 'horaalta')->textInput() ?>

    <?= $form->field($model, 'fechamodificacion')->textInput() ?>

    <?= $form->field($model, 'horamodificacion')->textInput() ?>

    <?= $form->field($model, 'foto')->textInput(['maxlength' => true]) ?>

   

   

    

    <?= $form->field($model, 'Trabajos')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'EstSup')->textInput(['maxlength' => true]) ?>

    

    <?= $form->field($model, 'ingreso')->textInput(['maxlength' => true]) ?>

    

    <?= $form->field($model, 'id_com_persona')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
