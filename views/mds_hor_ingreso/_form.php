<?php

use app\models\Mds_org_contacto;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\TimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_ingreso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-hor-ingreso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idcontacto')->widget(Select2::classname(), [
        'id' => 'cmb_contacto',
        'data' => ArrayHelper::map(
            Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                            join sds_com_persona p on p.idpersona=c.idpersona")->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
            'idcontacto',
            function ($model) {
                return $model->nombre . " " . $model->apellido;
            }
        ),
        'options' => ['placeholder' => 'Seleccionar Contacto ...', 'id' => 'cmb_contacto', 'disabled' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <div class="row">
        <div class="col-md-8">
            <?php
            $model->hora = date('H:i', strtotime(str_replace('/', '-', $model->fecha_hora)));
            $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora)));

            echo $form->field($model, 'fecha')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_entrega',
                    'class' => 'form-control input-md',
                    'disabled' => true
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ])->label('Fecha (dd/mm/yyyy)'); ?>
        </div>
        <div class="col-lg-4">
            <?=
            $form->field($model, 'hora')->widget(TimePicker::classname(), [
                //'options' => ['value' =>'00:00'],
                'options' => [
                    'id' => 'hora',
                    'disabled' => true,
                    'class' => 'form-control input-sm',
                ],
                'pluginOptions' => [
                    'showSeconds' => false,
                    'showMeridian' => false,
                    'minuteStep' => 15,
                    //'secondStep' => 5,
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'temperatura')->textInput(['type' => 'number', 'maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
        </div>
    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>