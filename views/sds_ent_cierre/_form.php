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
                'layout' => '{picker}{input}{remove}',
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
        <?php if ($model_entrega->tiene_numero) :  ?>
            <div class="form-check col-sm-3" style="padding-top: 28px;">
                <input type="checkbox" class="form-check-input" id="is_motivo_general">
                <label class="form-check-label" for="is_motivo_general"> Aplicar Motivo General</label>
            </div>
            <div id="asignar_motivo_general" class="col-sm-6" style="display: none;">
                <?= $form->field($model_entrega, "motivo_general")->dropDownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::find()->where("idconfiguraciontipo=" .
                            Sds_com_configuracion_tipo::TIPO_ENT_MOTIVO_CIERRE)
                            ->orderBy(['descripcion' => SORT_ASC])->all(),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    [
                        'prompt' => 'Seleccionar Motivo Cierre ...',
                        'id' => 'motivo_general'
                    ]
                ); ?>
            </div>
        <?php else : ?>
            <div class="col-sm-6" style="padding-top: 28px;">
                <h5><b>Pendientes de Rendición: </b><?= $model_entrega->saldo ?></h5>
            </div>
        <?php endif; ?>
    </div>
    <div class="panel panel-default" style="padding-top: 15px;">
        <div class="panel-heading" style="padding: 5px;">
            <h4><i class="fas fa-list-ol"></i><?= !$model_entrega->tiene_numero ? " Motivos Cierre" : " Números a rendir" ?></h4>
        </div>
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
                                //agrego campo hidden para identrega, sino no me lo toma
                                echo Html::activeHiddenInput($model_cierre, "[{$i}]identrega");
                                // necessary for update action.
                                if (!$model_cierre->isNewRecord) {
                                    echo Html::activeHiddenInput($model_cierre, "[{$i}]idcierre");
                                }
                                if ($model_entrega->tiene_numero) :
                                    echo Html::activeHiddenInput($model_cierre, "[{$i}]numero");
                                    echo Html::activeHiddenInput($model_cierre, "[{$i}]cantidad")
                                ?>
                                    <div class="col-sm-2" style="margin-top:25px;">
                                        <h5><b>Número: </b><?= $model_cierre->numero ?></h5>
                                    </div>
                                    <div class="col-sm-6">
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
                                            ]
                                        ); ?>
                                    </div>
                                <?php else :
                                    echo Html::activeHiddenInput($model_cierre, "[{$i}]motivo")
                                ?>
                                    <div class="col-sm-7" style="margin-top:25px;">
                                        <h5><b>Motivo: </b><?= Sds_com_configuracion::findOne($model_cierre->motivo)->descripcion ?></h5>
                                    </div>
                                    <div class="col-sm-1">
                                        <?= $form->field($model_cierre, "[{$i}]cantidad")->textInput(['maxlength' => true]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class='col-md-4'>
                        <?php
                        if ($model_entrega->adjunto_cierre == null) {
                            echo $form->field($model_entrega, "archivo_adjunto_cierre", ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    //'name' => 'i1',
                                    'options' => ['accept' => 'image/*,.pdf'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        //'showPreview' => false,
                                        'allowedFileExtensions' => ['pdf', 'jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                        'showCaption' => false,
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/sds_ent_entrega/update']),
                                        'maxFileSize' => 10000,
                                        /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                        'previewFileType' => 'file',
                                        'initialCaption' => false,
                                        'fileActionSettings' => [
                                            'showRemove' => true,
                                            'showUpload' => false,
                                        ]
                                        //'minFileCount' => 1,
                                        // 'validateInitialCount' => true,
                                    ],
                                ])->label('Archivo de Acta');
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    var cantidad_cierres = <?= sizeof($models_cierres) ?>;

    $("#is_motivo_general").on("change", function() {
        let asignar = $("#is_motivo_general").is(':checked');
        if (asignar) {
            $("#asignar_motivo_general").show();
        } else {
            $("#asignar_motivo_general").hide();
        }
    });
    $("#motivo_general").on("change", function() {
        for (let i = 0; i < cantidad_cierres; i++) {
            $("#sds_ent_cierre-" + i + "-motivo").val($("#motivo_general").val());
        }
    });
    //sds_ent_cierre-0-motivo

    //Dejo estos métodos por si son necesarios después: son de la documentación de la librería.
    /* $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
        console.log("beforeInsert");
    });

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        console.log("afterInsert");
    });

    $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
        if (!confirm("Are you sure you want to delete this item?")) {
            return false;
        }
        return true;
    });

    $(".dynamicform_wrapper").on("afterDelete", function(e) {
        console.log("Deleted item!");
    });

    $(".dynamicform_wrapper").on("limitReached", function(e, item) {
        alert("Limit reached");
    }); */
</script>