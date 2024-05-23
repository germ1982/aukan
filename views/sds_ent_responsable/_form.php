<?php

use app\models\Mds_org_organismo_externo;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_responsable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-ent-responsable-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row" style="padding-top: 2%">
        <div class="col-md-12">
            <?= $form->field($model, 'responsable')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-6'>
            <?= $form->field($model, 'dni')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-7'>
            <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <?= $form->field($model, 'idorganismoexterno')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Mds_org_organismo_externo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismoexterno', 'descripcion'),
                    'options' => [
                        'placeholder' => 'Seleccionar Organismo Externo ...',
                        'id' => 'cmb_externo',
                        'tabIndex' => '1',
                        'disabled' => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: 2%">
        <div class='col-md-6'>
            <?php
            if ($model->dni_frente == null) {
                echo $form->field($model, 'archivo_dni_frente', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        //'name' => 'i1',
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            //'showPreview' => false,
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_ent_entrega/update']),
                            'maxFileSize' => 1000,
                            /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                            'previewFileType' => 'image',
                            'initialCaption' => $model->dni_frente,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ]
                            //'minFileCount' => 1,
                            // 'validateInitialCount' => true,
                        ],
                    ])->label('DNI FRENTE');
            } else {
                echo $form->field($model, 'archivo_dni_frente', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_ent_entrega/update']),
                            'maxFileSize' => 1000,
                            'previewFileType' => 'image',
                            'initialPreview' => [
                                Html::img($model->dni_frente, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                            ],
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'initialCaption' => $model->dni_frente,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                            ]
                        ],
                        'pluginEvents' => [
                            "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                            "filereset" => "function() {  }",
                        ]
                    ])->label('DNI FRENTE');
            }
            ?>
        </div>
        <div class='col-md-6'>
            <?php
            if ($model->dni_dorso == null) {
                echo $form->field($model, 'archivo_dni_dorso', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        //'name' => 'i1',
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            //'showPreview' => false,
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_ent_responsable/update']),
                            'previewFileType' => 'image',
                            'maxFileSize' => 1000,
                            /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                            'initialCaption' => $model->dni_frente,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ]
                            //'minFileCount' => 1,
                            // 'validateInitialCount' => true,
                        ],
                    ])->label('DNI FRENTE');
            } else {
                echo $form->field($model, 'archivo_dni_dorso', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'previewFileType' => 'image',
                            'resizeImages' => true,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_ent_responsable/update']),
                            'maxFileSize' => 1000,
                            'initialPreview' => [
                                Html::img($model->dni_dorso, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                            ],
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'initialCaption' => $model->dni_dorso,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                            ]
                        ],
                        'pluginEvents' => [
                            "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                            "filereset" => "function() {  }",
                        ]
                    ])->label('DNI DORSO');
            }
            ?>
        </div>
    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>