<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StockInformaticaEgreso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-informatica-egreso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'idpersona_solicitante')->textInput() ?>

    <?= $form->field($model, 'idempleado_autorizacion')->textInput() ?>

    <?= $form->field($model, 'idempleado_despacha')->textInput() ?>

    <?= $form->field($model, 'idpersona_recibe')->textInput() ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
