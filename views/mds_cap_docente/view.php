<?php

use app\models\Mds_cap_instancia;
use app\models\Mds_cap_docente;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_docente */
?>
<div class="mds-cap-docente-view">


    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <!-- ###################### -->
        <div class="col-md-4">
            <!-- busca el docente por DNI -->
            <?= $form
                ->field($model, 'dni')
                ->textInput([
                    'id' => 'txtDNI',
                    'disabled' => !$model->isNewRecord,
                ]) ?>
        </div>

        <div class="col-md-2" style="padding-top:25px;">
            <?php echo Html::a(
                '<i class="glyphicon glyphicon-search"></i>',
                null,
                [
                    'name' => 'btn_dni_benef',
                    'id' => 'btn_dni_benef',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-primary',
                    'title' => Yii::t('app', 'Consultar DNI'),
                    'disabled' => !$model->isNewRecord,
                ]
            ); ?>
        </div>

        <div class="col-md-6" style="padding-top:30px;" id="txt_mensaje">
        </div>
    </div>

    <div class="row">
        <!-- ###################### -->
        <div class="col-md-4">
            <?= $form
                ->field($model, 'nombre')
                ->textInput(['id' => 'nombre_persona']) ?>
        </div>

        <div class="col-md-4">
            <?= $form
                ->field($model, 'apellido')
                ->textInput(['id' => 'apellido_persona']) ?>
        </div>

        <div class="col-md-4">
            <?php
            if ($model->fecha_nacimiento != null) {
                $model->fecha_nacimiento = date(
                    'd/m/Y',
                    strtotime(str_replace('/', '-', $model->fecha_nacimiento))
                );
            }
            echo $form
                ->field($model, 'fecha_nacimiento')
                ->widget(DatePicker::ClassName(), [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'fecha_nacimiento',
                        'class' => 'form-control input-md',
                        'disabled' => false,
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'endDate' => date('d/m/Y'),
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ],
                ])
                ->label('Fecha de Nacimiento');
            ?>
        </div>
    </div>

    <div class="row">
        <!-- ###################### -->
        <div class="col-md-4">
            <?= $form
                ->field($model, 'sexo')
                ->dropdownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguracionesActivas(
                            Sds_com_configuracion_tipo::TIPO_GENERO,
                            false
                        ),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    [
                        'id' => 'sexo_persona',
                        'prompt' => 'Seleccionar Genero ...',
                        'tabindex' => '1',
                    ]
                ) ?>
        </div>

        <div class="col-md-4">
            <?= $form
                ->field($model, 'nacionalidad')
                ->dropdownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguracionesActivas(
                            Sds_com_configuracion_tipo::TIPO_NACIONALIDAD,
                            false
                        ),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    [
                        'id' => 'nacionalidad_persona',
                        'prompt' => 'Seleccionar Nacionalidad ...',
                        'tabindex' => '1',
                    ]
                ) ?>
        </div>

        <div class="col-md-4">
            <?= $form
                ->field($model, 'localidad')
                ->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Sds_com_localidad::getLocalidadesMostrar(),
                        'idlocalidad',
                        'descripcion'
                    ),
                    'options' => [
                        'placeholder' => 'Seleccionar Localidad ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>
        </div>
    </div>


    <div class="row">
        <!-- ###################### -->
        <div class="col-md-8">
            <?= $form
                ->field($model, 'email')
                ->textInput(['id' => 'email', 'maxlength' => true])
                ->label('E-mail') ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'telefono')
                ->textInput(['id' => 'telefono']) ?>
        </div>
    </div>

    <div class="row">
        <!-- ###################### -->
        <div class="col-md-8">
            <?= $form
                ->field($model, 'datos_docente')
                ->textarea(['rows' => 3]) ?>
        </div>

        <div class="col-md-4">
            <div class="input-group">
                <?= $form
                    ->field($model, 'profesion_corta')
                    ->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguracionesActivas(
                                Sds_com_configuracion_tipo::CAP_DOCENTE_PROFCORTA,
                                true
                            ),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => '-- Seleccione profesiòn--',
                            'id' =>
                                'config_' .
                                Sds_com_configuracion_tipo::CAP_DOCENTE_PROFCORTA,
                        ]
                    )
                    ->label('Profesión Corta') ?>


            </div>
        </div>
    </div>

    <div class="row">
        <!-- ###################### -->
        <div class="col-md-3">
            <?= $form->field($model, 'firma_digital')->dropDownList(
                [
                    Mds_cap_docente::FIRMA_DIGITAL_SI => 'Si',
                    Mds_cap_docente::FIRMA_DIGITAL_NO => 'No',
                ],
                ['prompt' => 'Posee firma digital?']
            ) ?>
        </div>

        <div class="col-md-5">
            <?= $form
                ->field($model, 'cargo_certificado')
                ->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class='col-md-8'>
            <?php if ($model->firma == null) {
                echo $form
                    ->field($model, 'temp_imagen', [
                        'enableClientValidation' => true,
                        'enableAjaxValidation' => false,
                    ])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => [
                                'jpg',
                                'jpeg',
                                'gif',
                                'png',
                                'bmp',
                            ],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to([
                                '/mds_cap_instancia/update',
                            ]),
                            'maxFileSize' => 100000,
                            'previewFileType' => 'file',
                            'initialCaption' => $model->firma,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ],
                        ],
                    ]);
            } else {
                echo $form
                    ->field($model, 'temp_imagen', [
                        'enableClientValidation' => true,
                        'enableAjaxValidation' => false,
                    ])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => [
                                'jpg',
                                'jpeg',
                                'gif',
                                'png',
                                'bmp',
                            ],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to([
                                '/mds_cap_instancia/update',
                            ]),
                            'maxFileSize' => 100000,
                            'previewFileType' => 'file',
                            'initialPreview' => [
                                Html::img(
                                    'uploads/instancias/firmas/' .
                                        $model->firma,
                                    [
                                        'class' => 'file-preview-image',
                                        'style' =>
                                            'width:100%; text-align: center',
                                    ]
                                ),
                            ],
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'initialCaption' => $model->firma,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                            ],
                        ],
                        'pluginEvents' => [
                            'fileclear' =>
                                "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                            'filereset' => 'function() {  }',
                        ],
                    ]);
            } ?>
        </div>
    </div>

    <div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
        <div class="col-md-12">
            <section class="panel panel-featured panel-featured-default">
                <header class="panel-heading">
                    <h3 id="abm_configuracion_title" class="panel-title">
                    </h3>
                </header>
                <div class="panel-body" id="abm_configuracion_content">
                </div>
            </section>
        </div>
    </div>

    <?= $form
        ->field($model, 'idpersona')
        ->hiddenInput(['id' => 'hidden_nueva_persona'])
        ->label(false) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                'class' => $model->isNewRecord
                    ? 'btn btn-success'
                    : 'btn btn-primary',
            ]) ?>
        </div>-->
    <?php } ?>

    <?php ActiveForm::end(); ?>


</div>