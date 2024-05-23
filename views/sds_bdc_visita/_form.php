<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_visita */
/* @var $form yii\widgets\ActiveForm */

    //Alerts Success y Error:
    //if(Yii::$app->session->hasFlash('save_equipo')):
    if(Yii::$app->session->hasFlash('save')):?>
        <div class="alert alert-success alert-dismissable" id="alert-save">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
            <b><?= Yii::$app->session->getFlash('save') ?></b>
        </div>
    <?php endif;
    if(Yii::$app->session->hasFlash('fail_save')):?>
        <div class="alert alert-danger alert-dismissable" id="alert-fail-save">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <span class="text-md"><i class="icon fas fa-times"></i> ¡Por favor intente nuevamente!</span>
            <b><?= Yii::$app->session->getFlash('fail_save') ?></b>
        </div>
    <?php endif;?>

<div class="sds-bdc-visita-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php if(!$model->isNewRecord){
        $model->fecha = date('d/m/Y', strtotime($model->fecha));
    }
    else{
        $model->fecha = date('d/m/Y');
    }
    ?>
    <?= $form->field($model,'fecha')->widget(DatePicker::class, [
        'options' => ['placeholder' => 'Seleccione ...', 'readonly' => true],
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'language' => 'es',
        'pluginOptions' => [
            'format' => 'dd/mm/yyyy',
            'autoclose' => true,
            'todayHighlight' => true,
            'orientation' => 'bottom left',
            
        ],
    ]
    )?>
    <?= $form->field($model, 'sector')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            $sectores,
            'idconfiguracion',
            'descripcion'
        ),
        'options' => [
            'placeholder' => '- Seleccionar Sector -'
        ],
        'pluginOptions' => [
            'allowClear' => false
            ]
    ]);?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>


	<?php 
        if (!Yii::$app->request->isAjax){?>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	<?php
    } 
    ?>

    <?php ActiveForm::end(); ?>
</div>
<script>
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    });
    
    $(document).ready(()=>{
        if($('#alert-save').css('display')!='none'){
            setTimeout(() => {
                $('#alert-save').hide();
        }, 1500);
        }
    });
</script>  
