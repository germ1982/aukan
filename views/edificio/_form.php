<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Edificio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="edificio-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion_fija')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion_gestion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idlocalidad')->textInput() ?>

    <?= $form->field($model, 'direccion_calle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direccion_altura')->textInput() ?>

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'geolocalizacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'activo')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
