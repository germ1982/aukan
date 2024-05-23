<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Telefonia_vista_integradora */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="telefonia-vista-integradora-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lineanro')->textInput() ?>

    <?= $form->field($model, 'cuenta')->textInput() ?>

    <?= $form->field($model, 'empresa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ultimo_movimiento')->textInput() ?>

    <?= $form->field($model, 'organismo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dependecia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'responsable')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'equipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imei')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plan')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
