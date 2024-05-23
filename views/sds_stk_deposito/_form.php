<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_deposito */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-stk-deposito-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php 
                    if ($model->isNewRecord)
                        {$aux = 1;}
                    else
                        {$aux = $model->activo;}
                    echo $form->field($model, 'activo')->dropDownList(['0'=>"no",'1'=>"si",],['value'=>$aux])->label('Activo')
                ?>
            </div>
        </div>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
