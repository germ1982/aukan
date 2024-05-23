<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_veh_mantenimiento */
/* @var $form yii\widgets\ActiveForm */

if(Yii::$app->session->hasFlash('success')) : ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <b><?= Yii::$app->session->getFlash('success') ?></b>
    </div>
<?php endif; 
if(Yii::$app->session->hasFlash('faild')) : ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-times"></i> ¡UPS!</h4>
        <b><?= Yii::$app->session->getFlash('faild') ?></b>
    </div>
<?php endif; ?>

<div class="sds-veh-mantenimiento-form">

    <?php
    if ($model->fecha != null) {
        $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
    }
    $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'fecha')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date_fecha',
                'language' => 'es',
                'readonly' => true,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_desde',
                    'class' => 'form-control input-md',
                    'disabled' => false
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ]);?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'km')->textInput(['type'=>'number'])?>
        </div>
    </div>

    <?= $form->field($model, 'detalle')->textarea(['rows' => 6]) ?>



    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>