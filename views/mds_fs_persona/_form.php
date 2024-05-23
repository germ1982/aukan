<?php

use app\models\Mds_fs_persona;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;

use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_fs_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-fs-persona-form">
    <style>
        div.required label:after {
            content: " *";
            color: red;
        }
    </style>
    <?php $form = ActiveForm::begin(); ?>
    ESTADO
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <?php echo $form->field($model, 'estado')->widget(Select2::classname(), [
                        'data' => $model->estados,
                        'options' => [
                            'placeholder' => 'Seleccionar ...',
                            'id' => 'estado',
                        ]
                    ])->label('Modificar Estado')
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    DATOS PERSONALES
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <?= $form->field($model, 'dni')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?php
                    if ($model->fecha_nacimiento != null) {
                        $fn = $model->fecha_nacimiento;
                        $model->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                    } else {
                        $fn = null;
                    }
                    echo $form->field($model, 'fecha_nacimiento')->widget(DatePicker::ClassName(), [
                        'name' => 'check_issue_date',
                        'language' => 'es',
                        'readonly' => false,
                        'removeButton' => false,
                        'layout' => '{picker}{input}{remove}',
                        'options' => [
                            'id' => 'fecha_nacimiento',
                            'class' => 'form-control input-md',
                            'disabled' => false,
                            //'onchange' =>   'actualizardatos()',                    
                        ],
                        'pluginOptions' => [
                            'value' => null,
                            'format' => 'dd/mm/yyyy',
                            'endDate' => date('d/m/Y'),
                            'todayHighlight' => true,
                            'autoclose' => true,
                        ]
                    ])->label('Fecha Nacimiento'); ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'lugar_nacimiento')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?php
                    echo
                    $form->field($model, 'nacionalidad')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => [
                            'placeholder' => 'Seleccionar ...',
                            'id' => 'cmb_nacionalidad',
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    echo
                    $form->field($model, 'genero')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_GENERO, false),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => [
                            'placeholder' => 'Seleccionar ...',
                            'id' => 'cmb_genero',
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    echo
                    $form->field($model, 'estado_civil')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_SIT_CONYUGAL, false),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => [
                            'placeholder' => 'Seleccionar ...',
                            'id' => 'cmb_estado_civil',
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'domicilio')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?php
                    if ($model->idlocalidad == '') {
                        $model->la_provincia = 58;
                        $model->idlocalidad = 58035070;

                        $una_prov = Sds_com_localidad::find()
                            ->select(['idprovincia'])
                            ->where(['idlocalidad' => $model->idlocalidad])
                            ->one();
                        $model->la_provincia = $una_prov->idprovincia;
                    } else {
                        //echo 'el id localidad es: '.$model->id_localidad;
                        $una_prov = Sds_com_localidad::find()
                            ->select(['idprovincia'])
                            ->where(['idlocalidad' => $model->idlocalidad])
                            ->one();
                        $model->la_provincia = $una_prov->idprovincia;
                    }

                    ?>
                    <?= $form->field($model, 'idprovincia')
                        ->widget(
                            Select2::classname(),
                            [
                                'data' => ArrayHelper::map(
                                    Sds_com_provincia::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                    'idprovincia',
                                    'descripcion'
                                ),
                                'options' => [
                                    'placeholder' => 'Seleccionar Provincia ...',
                                    'id' => 'cmb_provincia',
                                    'onchange' =>   'cargarLocalidades();',
                                    'disabled' => true
                                ],

                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]
                        )
                        ->label('Provincia');
                    ?>

                </div>
                <div class="col-md-6">

                    <?php
                    if ($model->idlocalidad == '') {
                        echo
                        $form->field($model, 'idlocalidad')->widget(Select2::classname(), [
                            /*'data' => ArrayHelper::map(
                                    Mds_rum_localidad::find()->orderBy(['nombre' => SORT_ASC])->all(),
                                    'id',
                                    'nombre'
                                ),*/
                            'options' => [
                                'placeholder' => 'Seleccionar ...',
                                'id' => 'cmb_localidad',
                            ],

                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])
                            ->label("Localidad");
                    } else {
                        //echo 'el id localidad es: '.$model->id_localidad;
                        echo
                        $form->field($model, 'idlocalidad')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Sds_com_localidad::find()
                                    ->where(['idprovincia' => $una_prov->idprovincia])
                                    ->orderBy(['descripcion' => SORT_ASC])
                                    ->all(),
                                'idlocalidad',
                                'descripcion'
                            ),
                            'options' => [
                                'placeholder' => 'Seleccionar ...',
                                'id' => 'cmb_localidad',
                            ],

                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    }

                    ?>

                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'tiempo_provincia')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?php
                    echo
                    $form->field($model, 'nivel_escolaridad')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                                ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO])
                                ->orderBy(['idconfiguracion' => SORT_ASC])
                                ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => [
                            'placeholder' => 'Seleccionar ...',
                            'id' => 'nivel_escolaridad',
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'profesion')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'telefono_alternativo')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'grupo_familiar')->textArea(['rows' => 6, 'maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    INFORMACION GENERAL
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'inscripto_rua_check')->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onText' => 'SI',
                                'offText' => 'NO'
                            ]
                        ]); ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'inscripto_rua')->textArea(['rows' => 6, 'maxlength' => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'motivo_fs')->textArea(['rows' => 6, 'maxlength' => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'acuerdo_familia')->textArea(['rows' => 6, 'maxlength' => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'conocimiento_programa')->textArea(['rows' => 6, 'maxlength' => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'disponibilidad_horaria')->textArea(['rows' => 6, 'maxlength' => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'franja_etaria')->textArea(['rows' => 6, 'maxlength' => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'consulta')->textArea(['rows' => 6, 'maxlength' => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    INFORME ADJUNTO
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div>
                        <?php if ($model->informe_adjunto_path == null) : ?>
                            <?= $form->field($model, 'temp_informe_adjunto_path', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => '.pdf'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => ['pdf'],
                                        'showCaption' => false,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_com_intervencion/update']),
                                        'maxFileSize' => 1000000000,
                                        'previewFileType' => 'file',
                                        'initialCaption' => false,
                                        'fileActionSettings' => [
                                            'showRemove' => true,
                                            'showUpload' => false,
                                        ]
                                    ],
                                ]);

                            ?>
                        <?php else : ?>
                            <?= $form->field($model, 'temp_informe_adjunto_path', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => '.pdf'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => ['pdf'],
                                        'showCaption' => false,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_org_informe/update']),
                                        'maxFileSize' => 1000000000,
                                        'previewFileType' => 'file',
                                        'initialPreview' => [
                                            Url::to('@web/uploads/familiassolidarias/'.$model->idfspersona.'/informe/'.$model->informe_adjunto_path, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                                        ],
                                        'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                        'initialPreviewFileType' => Mds_fs_persona::getExtension($model->informe_adjunto_path), // image is the default and can be overridden in config below
                                        'overwriteInitial' => true,
                                        'autoReplace' => true,
                                        'fileActionSettings' => [
                                            'showRemove' => false,
                                            'showUpload' => false,
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "fileclear" => "function() { console.log('fileclear'); $('#borrar').val(true); console.log($('#borrar').val());}",
                                        "filereset" => "function() {  }",
                                    ]
                                ]);
                            ?>
                        <?php endif; ?>
                        <?= $form->field($model, 'borrar_adjunto')->hiddenInput(['id' => 'borrar'])->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>