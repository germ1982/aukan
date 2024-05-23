<?php

use app\models\Sds_com_provincia;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_localidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-com-localidad-form">

    <?php $form = ActiveForm::begin();?>
    <div class="row">
        <div class="col-md-8">
            <?=$form->field($model, 'idprovincia')
            ->widget(Select2::classname(),
                [
                    'data' => ArrayHelper::map(
                        Sds_com_provincia::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                        'idprovincia',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccione Provincia ...',
                        'id' => 'cmb_provincia'],

                    'pluginOptions' => [
                        'allowClear' => false,
                    ],
                ])
            ->label('Provincia');
?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <?=$form->field($model, 'descripcion')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-4">
            <?=$form->field($model, 'codigo_postal')->textInput(['maxlength' => true])?>
        </div>
    </div>

    <?=$form->field($model, 'activo')->checkbox(["checked" => true])?>


    <?php if (!Yii::$app->request->isAjax) {?>
        <div class="form-group">
            <?=Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
        </div>
    <?php }?>

    <?php ActiveForm::end();?>

</div>