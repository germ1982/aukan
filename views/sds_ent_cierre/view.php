<?php

use app\models\Mds_org_documento;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_tipo;
use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_cierre */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-ent-cierre-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="row">
        <div class="col-md-3">
            <?php
            $model_entrega->fecha_cierre = date('d/m/Y', strtotime(str_replace('/', '-', $model_entrega->fecha_cierre)));

            echo $form->field($model_entrega, 'fecha_cierre')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}',
                'options' => [
                    'id' => 'fecha_cierre',
                    'tabIndex' => '1',
                    'class' => 'form-control input-md',
                    'disabled' => false
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
    </div>    
    <div class="panel panel-default">
        <?php if ($model_entrega->estado_cierre!=2): ?>
        <div class="panel-heading" style="padding: 5px;">
            <h4><i class="fas fa-list-ol"></i><?= !$model_entrega->tiene_numero ? " Motivos Cierre" : " Números a rendir" ?></h4>
        </div>
        <?php endif; ?>
        <?php if ($model_entrega->estado_cierre!=2): ?>
        <div class="panel-body">
            <?php
            DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 4, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $models_cierres[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'motivo',
                    'cantidad',
                ],
            ]); ?>
            <div class="container-items" style="height: 260px;overflow-y: auto;overflow-x: hidden;">
                <!-- widgetContainer -->
                <?php foreach ($models_cierres as $i => $model_cierre) : ?>
                    <div class="item panel panel-default">
                        <!-- widgetBody -->
                        <div class="panel-body" style="padding:0px;">
                            <div class="row">
                                <?php
                                if ($model_entrega->tiene_numero) :
                                ?>
                                    <div class="col-sm-3" style="margin-top:25px;">
                                        <h5><b>Número: </b><?= $model_cierre->numero ?></h5>
                                    </div>
                                    <div class="col-sm-7">
                                        <?= $form->field($model_cierre, "[{$i}]motivo")->dropDownList(
                                    ArrayHelper::map(
                                        Sds_com_configuracion::find()->where("idconfiguraciontipo=" .
                                                    Sds_com_configuracion_tipo::TIPO_ENT_MOTIVO_CIERRE)
                                                    ->orderBy(['descripcion' => SORT_ASC])->all(),
                                        'idconfiguracion',
                                        'descripcion'
                                    ),
                                    [
                                                'prompt' => 'Seleccionar Motivo Cierre ...',
                                                'disabled' => true
                                            ]
                                ); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="col-sm-8" style="margin-top:25px;">
                                        <h5><b>Motivo: </b><?= Sds_com_configuracion::findOne($model_cierre->motivo)->descripcion ?></h5>
                                    </div>
                                    <div class="col-sm-1">
                                        <?= $form->field($model_cierre, "[{$i}]cantidad")->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div> 
                <?php endforeach; ?>               
            </div>
            <?php DynamicFormWidget::end(); ?>
            <?php endif; ?>
            <div class="row">
                <div class='col-md-4'>
                    <?php
                    echo $form->field($model_entrega, "archivo_adjunto_cierre", ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*,.pdf'],
                            'language' => 'es',
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                                'showCaption' => false,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showClose' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/sds_ent_entrega/update']),
                                'maxFileSize' => 10000,
                                'previewFileType' => 'file',
                                'initialPreview' => $model_entrega->adjunto_cierre!=null ?[
                                    Url::to('@web/' . $model_entrega->adjunto_cierre, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                                ]:false,
                                'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                'initialPreviewFileType' => Mds_org_documento::getExtension($model_entrega->adjunto_cierre), // image is the default and can be overridden in config below
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                ]
                            ],
                            'pluginEvents' => [
                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                "filereset" => "function() {  }",
                            ]
                        ])->label('Archivo de Acta');
                    ?>
                </div>
            </div>
        </div>
    </div>    
    <?php ActiveForm::end(); ?>
</div>