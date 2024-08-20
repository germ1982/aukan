<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPerfilPermiso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-perfil-permiso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idperfil')->textInput() ?>

    <?= $form->field($model, 'idtipopermiso')->textInput() ?>

    <?= $form->field($model, 'idacceso')->textInput() ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
