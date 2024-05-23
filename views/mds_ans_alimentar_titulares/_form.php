<?php

use app\models\mds_ans_alimentar_titulares as ModelsMds_ans_alimentar_titulares;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
date_default_timezone_set('America/Argentina/Buenos_Aires');

$this->title = "Modificar Titulares Tarjeta Alimentar";
?>

<div class="mds-ans-alimentar-titulares-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'apellido')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['disabled'=>true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'dni')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cuil')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'estado')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'provincia')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'departamento')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'localidad')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'municipio')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'totalHijos')->textInput(['disabled'=>true,'maxlength' => true])->label('Cantidad de Hijos') ?>
        </div>
        <div class="col-md-3">
            <?php
                // $form->field($model, 'embarazo')->dropDownList([
               // null => "",
               // ModelsMds_ans_alimentar_titulares::EMBARAZO_N => "NO",
               // ModelsMds_ans_alimentar_titulares::EMBARAZO_S=> "SI"
               //]) 
            ?>
            <?php echo $form->field($model, 'embarazo')->        
                    dropDownList(['0' => 'No', '1' => 'Si'],
                    [  
                        'disabled'=>true
                    ])
                    ->label('Embarazo'); 
            ?>
        </div>
        </div>

        <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'estado_entrega')->dropDownList([
                null => "",
                ModelsMds_ans_alimentar_titulares::PENDIENTE => "Pendiente",
                ModelsMds_ans_alimentar_titulares::ENTREGADA => "Entregada"
            ]) ?>
        </div>
        <div class="col-md-3">
        <?php     
        if ($model->fecha_hora != null) {
                //$model->fecha_hora = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora)));
                $model->fecha_hora =  $model->fecha_hora;

            }
        else
            {
             //   $model->fecha_hora =date('d-m-Y H:i:s');
                //$model->fecha_publicacionfin = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_publicacionfin)));
            }
        ?>
                <?= $form->field($model, 'fecha_hora')->widget(DateTimePicker::ClassName(), [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    //'type' => DateTimePicker::TYPE_INPUT,
                                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), le saco la opción de remover
                                                    'layout' => !$model->isNewRecord ? '{picker}{input}' : '{picker}{input}{remove}',
                                                    'options' => [
                                                        'id' => 'fecha_hora',
                                                        'class' => 'form-control input-md',
                                                        //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito la selección del date.
                                                        "disabled" => false
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        //'format' => 'd-m-Y H:i:s',
                                                        //'endDate' => date('d-m-Y'),
                                                        'todayHighlight' => true,
                                                        'autoclose' => false,
                                                    ]
                                                ])->label('Día y hora'); ?>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>


