<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-com-persona-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'documento')->textInput(["id" => "txtDNI","readonly" => "true"]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'apellido')->textInput(['maxlength' => true, "readonly" => "true"]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, "readonly" => "true"]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'nacionalidad')->dropDownList(
                ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD), 'idconfiguracion', 'descripcion'),
                ['prompt' => 'Seleccionar Nacionalidad ...', 'disabled' => true]
            );
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'genero')->dropDownList(
                ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_GENERO), 'idconfiguracion', 'descripcion'),
                ['prompt' => 'Seleccionar Género ...', 'disabled' => 'true']
            );
            ?>
        </div>
        <div class="col-md-4">
            <?php
            if ($model->fecha_nacimiento != null) {
                $model->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
            }
            echo $form->field($model, 'fecha_nacimiento')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_nacimiento',
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
            ])->label('Fecha de Nacimiento');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <?= $form->field($model, 'conviviente')->checkBox(['id' => 'check_conviviente','disabled'=>true]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<script>
    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }
</script>