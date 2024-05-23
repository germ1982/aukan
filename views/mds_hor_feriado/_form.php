<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_feriado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-hor-feriado-form">

	<?php $form = ActiveForm::begin(); ?>
	
	<div class="row">

            <div class="col-md-4">
                <?php
                if ($model->fecha != null) {
                    $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
                }
                echo $form->field($model, 'fecha')->widget(DatePicker::ClassName(), [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'fecha',
                        'class' => 'form-control input-md',
                        'disabled' => false
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        //'endDate' => date('d/m/Y'),
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ]
                ])->label('Fecha'); ?>
            </div>
        </div>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
