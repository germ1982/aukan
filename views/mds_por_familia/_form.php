<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\mds_por_familia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-por-familia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'localidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dni')->textInput() ?>

    <?= $form->field($model, 'cuil')->textInput() ?>

    <?= $form->field($model, 'responsable_cobro')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dni_responsable')->textInput() ?>

    <?= $form->field($model, 'importe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'programa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subprograma')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'responsable_certificacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expediente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desde')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hasta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'F12')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'F15')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'F16')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'F17')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'F18')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'F19')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mes')->textInput() ?>

    <?= $form->field($model, 'anio')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
