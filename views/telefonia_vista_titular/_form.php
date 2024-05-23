<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Telefonia_vista_titular */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="telefonia-vista-titular-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lineanro')->textInput() ?>

    <?= $form->field($model, 'ultimo_movimiento')->textInput() ?>

    <?= $form->field($model, 'organismo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dependencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'responsable')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'movimientos')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
