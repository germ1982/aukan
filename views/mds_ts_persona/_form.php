<?php

use app\models\Mds_ts_persona;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\Url;

use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;
use app\models\Mds_ts_checklist;
use app\models\Sds_com_configuracion_tipo;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_ts_persona */
/* @var $form yii\widgets\ActiveForm */

function botonAltaConfiguracion($model, $tipo)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => $tipo]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . $tipo, 'style' => 'margin-top:25px',
        'tabIndex' => '-1',
        // "disabled" => !$model->isNewRecord,
        'onclick' => '
            $("#modal_abm").modal("show")
            .find("#content_abm")
            .load($(this).attr("value"));
            $("#header_abm").html("Agregar Tipo");
        '
    ]);
}
?>

<style>
    .mt-2 {
        margin-top: 2rem;
    }
</style>

<div class="mds-ts-persona-form">

    <?php $form = ActiveForm::begin(); ?>
    <b>CAMPAÑA Y ESTADO DE LA SOLICITUD</b>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'campania')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Sds_com_configuracion::getConfiguracionesSinOrden(Sds_com_configuracion_tipo::TIPO_TS_CAMPANIA, true),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    'options' => [
                        'placeholder' => 'Seleccionar una opción',
                        'tabIndex' => '1',
                        'id' => 'campania',

                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Seleccione una Campaña');
                ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'estado')->dropDownList(
                    [
                        Mds_ts_persona::SOLICITUD => "Pendiente de Evaluacion",
                        Mds_ts_persona::ACEPTADA => "Aceptada",
                        Mds_ts_persona::RECHAZADA => "Rechazada",
                    ],
                    ['prompt' => '-- Seleccione una opción --']
                ) ?>
            </div>
        </div>
    </div>
    <?php if ($model->tipo_beneficiario == 1) { ?>
        <br>
        <b>DATOS INSTITUCIÓN</b>
        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'nombre_institucion')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'domicilio_institucion')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <?= $form->field($model, 'tipo_institucion')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_TS_INSTITUCION, true),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            [
                                'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_TS_INSTITUCION,
                                'prompt' => 'Seleccione un Tipo de Institución',
                            ]
                        )
                        ?>
                        <span class="input-group-btn">
                            <?= botonAltaConfiguracion($model,  Sds_com_configuracion_tipo::TIPO_TS_INSTITUCION) ?>
                        </span>
                    </div>

                </div>

            </div>
        </div>
    <?php } ?>
    <br>
    <b>DATOS <?php if ($model->tipo_beneficiario) { ?>
            <span> RESPONSABLE INSTITUCION </span>
        <?php } else { ?>
            <span> BENEFICIARIOS </span>
        <?php } ?> </b>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <?= $form->field($model, 'dni')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-3">
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
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($model, 'domicilio')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>

                <div class="row">
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
                        <?= $form->field($model, 'la_provincia')
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
                                    ],

                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]
                            )
                            ->label('Provincia*');
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
                                ->label("Localidad*");
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
                            ])
                                ->label("Localidad*");
                        } ?>
                    </div>
                    <?php if ($model->tipo_beneficiario == 1) { ?>
                        <div class="col-md-6">
                            <?= $form->field($model, 'relacion_institucion')->textInput(['maxlength' => true]) ?>
                        </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <?= $form->field($model, 'nro_persona')->textInput(['maxlength' => true])->label('N° Persona (Boleta CALF)') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <b>DOCUMENTACIÓN ADJUNTA</b>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <!-- DNI -->
        <div class="row">
            <div class='col-md-6 mt-2' align="center" ;>
                <?php  // foto_dni_frente
                if ($model->foto_dni_frente == null) {
                    echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'showCancel' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_ts_persona/update']),
                                'maxFileSize' => 1000,
                                /* 'initialPreview'=>[
                                                Html::img($model->Foto,['class'=>'file-preview-image']),
                                                ], */
                                'previewFileType' => 'image',
                                'initialCaption' => $model->foto_dni_frente,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showZoom' => false,
                                    'showCaption' => false,
                                    'showCancel' => false
                                ]
                                //'minFileCount' => 1,
                                // 'validateInitialCount' => true,
                            ],
                        ])->label('FOTO DNI FRENTE:');
                } else {
                    if ((str_contains($model->foto_dni_frente, 'https:')) || (str_contains($model->foto_dni_frente, 'http:'))) {
                        $cad_preview = $model->foto_dni_frente;
                    } else {
                        $cad_preview = Url::base() . "/" . $model->foto_dni_frente;
                    }
                    echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*'],
                            'language' => 'es',
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                'showCaption' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'showClose' => false,
                                'showCancel' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_ts_persona']),
                                'maxFileSize' => 1000,
                                'previewFileType' => 'image',
                                'initialPreview' => [
                                    //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),

                                    Html::img($cad_preview, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                    //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->imagen);
                                ],
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'initialCaption' => $model->foto_dni_frente,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showZoom' => false,
                                    'showCaption' => false,
                                    'showCancel' => false
                                ]
                            ],
                            'pluginEvents' => [
                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                "filereset" => "function() {  }",
                            ]
                        ])->label('FOTO DNI FRENTE:');
                }
                ?>
            </div>

            <div class='col-md-6 mt-2' align="center" ;>
                <?php // foto_dni_dorso
                if ($model->foto_dni_dorso == null) {
                    echo $form->field($model, 'archivo_imagen2', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'showCancel' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_ts_persona/update']),
                                'maxFileSize' => 1000,
                                /* 'initialPreview'=>[
                                                Html::img($model->Foto,['class'=>'file-preview-image']),
                                                ], */
                                'previewFileType' => 'image',
                                'initialCaption' => $model->foto_dni_dorso,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showZoom' => false,
                                    'showCaption' => false,
                                    'showCancel' => false
                                ]
                                //'minFileCount' => 1,
                                // 'validateInitialCount' => true,
                            ],
                        ])->label('FOTO DNI DORSO:');
                } else {
                    if ((str_contains($model->foto_dni_dorso, 'https:')) || (str_contains($model->foto_dni_dorso, 'http:'))) {
                        $cad_preview = $model->foto_dni_dorso;
                    } else {
                        $cad_preview = Url::base() . "/" . $model->foto_dni_dorso;
                    }
                    echo $form->field($model, 'archivo_imagen2', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*'],
                            'language' => 'es',
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                'showCaption' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'showClose' => false,
                                'showCancel' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_ts_persona']),
                                'maxFileSize' => 1000,
                                'previewFileType' => 'image',
                                'initialPreview' => [
                                    //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                    Html::img($cad_preview, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                    //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->imagen);
                                ],
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'initialCaption' => $model->foto_dni_dorso,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showZoom' => false,
                                    'showCaption' => false,
                                    'showCancel' => false
                                ]
                            ],
                            'pluginEvents' => [
                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                "filereset" => "function() {  }",
                            ]
                        ])->label('FOTO DNI DORSO:');
                }
                ?>
            </div>

            <div class="col-md-6 mt-2" align="center" ;>
                <?php // factura_luz
                if ($model->factura_luz == null) : ?>
                    <?= $form->field($model, 'temp_archivo_adjunto', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*,.pdf'],
                            'language' => 'es',
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf'],
                                'showCaption' => false,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showClose' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_ts_persona/update']),
                                'maxFileSize' => 1000000000,
                                'previewFileType' => 'file',
                                'initialCaption' => false,
                                'fileActionSettings' => [
                                    'showRemove' => true,
                                    'showUpload' => false,
                                ]
                            ],
                        ])->label('FACTURA DE LUZ:');

                    ?>
                <?php else : ?>
                    <?php
                    if ((str_contains($model->factura_luz, 'https:')) || (str_contains($model->factura_luz, 'http:'))) {
                        $cad_preview = $model->factura_luz;
                    } else {
                        $cad_preview = Url::base() . "/" . $model->factura_luz;
                    }
                    ?>
                    <?= $form->field($model, 'temp_archivo_adjunto', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*,.pdf'],
                            'language' => 'es',
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf'],
                                'showCaption' => false,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showClose' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_ts_persona']),
                                'maxFileSize' => 1000000000,
                                'previewFileType' => 'file',
                                'initialPreview' => [
                                    Url::to($cad_preview, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                                ],
                                'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                'initialPreviewFileType' => Mds_ts_persona::getExtension($model->factura_luz), // image is the default and can be overridden in config below
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                ]
                            ],
                            'pluginEvents' => [
                                "fileclear" => "function() { console.log('fileclear'); $('#borrar').val(true);}",
                                "filereset" => "function() {  }",
                            ]
                        ])->label('FACTURA DE LUZ:');
                    ?>
                <?php endif; ?>
                <?= $form->field($model, 'borrar_adjunto1')->hiddenInput(['id' => 'borrar1'])->label(false) ?>

            </div> <?php if ($model->tipo_beneficiario) { ?>
                <!-- PARA INSTITUCIONES -->
                <div class="row">
                    <div class="col-md-6 mt-2" align="center" ;>
                        <?php // personeria_juridica
                        if ($model->personeria_juridica == null) : ?>
                            <?= $form->field($model, 'temp_personeria_juridica', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*,.pdf'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf'],
                                        'showCaption' => false,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_ts_persona/update']),
                                        'maxFileSize' => 1000000000,
                                        'previewFileType' => 'file',
                                        'initialCaption' => false,
                                        'fileActionSettings' => [
                                            'showRemove' => true,
                                            'showUpload' => false,
                                        ]
                                    ],
                                ])->label('PERSONERIA JURIDICA:');

                            ?>
                        <?php else : ?>
                            <?php
                            if ((str_contains($model->personeria_juridica, 'https:')) || (str_contains($model->personeria_juridica, 'http:'))) {
                                $cad_preview = $model->personeria_juridica;
                            } else {
                                $cad_preview = Url::base() . "/" . $model->personeria_juridica;
                            }
                            ?>
                            <?= $form->field($model, 'temp_personeria_juridica', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*,.pdf'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf'],
                                        'showCaption' => false,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_ts_persona']),
                                        'maxFileSize' => 1000000000,
                                        'previewFileType' => 'file',
                                        'initialPreview' => [
                                            Url::to($cad_preview, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                                        ],
                                        'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                        'initialPreviewFileType' => Mds_ts_persona::getExtension($model->personeria_juridica), // image is the default and can be overridden in config below
                                        'overwriteInitial' => true,
                                        'autoReplace' => true,
                                        'fileActionSettings' => [
                                            'showRemove' => false,
                                            'showUpload' => false,
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "fileclear" => "function() { console.log('fileclear'); $('#borrar').val(true);}",
                                        "filereset" => "function() {  }",
                                    ]
                                ])->label('PERSONERIA JURIDICA:');
                            ?>
                        <?php endif; ?>
                        <?= $form->field($model, 'borrar_adjunto_personeria')->hiddenInput(['id' => 'borrar1'])->label(false) ?>

                    </div>
                </div>
            <?php } else { ?>
                <!-- PARA PERSONAS -->
                <div class="row">
                    <div class="col-md-6 mt-2" align="center" ;>
                        <?php // recibo_sueldo
                        if ($model->recibo_sueldo == null) : ?>
                            <?= $form->field($model, 'temp_archivo_adjunto2', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*,.pdf'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf'],
                                        'showCaption' => false,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_ts_persona/update']),
                                        'maxFileSize' => 1000000000,
                                        'previewFileType' => 'file',
                                        'initialCaption' => false,
                                        'fileActionSettings' => [
                                            'showRemove' => true,
                                            'showUpload' => false,
                                        ]
                                    ],
                                ])->label('RECIBO DE SUELDO:');

                            ?>
                        <?php else : ?>
                            <?php
                            if ((str_contains($model->recibo_sueldo, 'https:')) || (str_contains($model->recibo_sueldo, 'http:'))) {
                                $cad_preview = $model->recibo_sueldo;
                            } else {
                                $cad_preview = Url::base() . "/" . $model->recibo_sueldo;
                            }
                            ?>
                            <?= $form->field($model, 'temp_archivo_adjunto2', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*,.pdf'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf'],
                                        'showCaption' => false,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_ts_persona']),
                                        'maxFileSize' => 1000000000,
                                        'previewFileType' => 'file',
                                        'initialPreview' => [
                                            Url::to($cad_preview, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                                        ],
                                        'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                        'initialPreviewFileType' => Mds_ts_persona::getExtension($model->recibo_sueldo), // image is the default and can be overridden in config below
                                        'overwriteInitial' => true,
                                        'autoReplace' => true,
                                        'fileActionSettings' => [
                                            'showRemove' => false,
                                            'showUpload' => false,
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "fileclear" => "function() { console.log('fileclear'); $('#borrar').val(true);}",
                                        "filereset" => "function() {  }",
                                    ]
                                ])->label('RECIBO DE SUELDO:');
                            ?>
                        <?php endif; ?>
                        <?= $form->field($model, 'borrar_adjunto2')->hiddenInput(['id' => 'borrar2'])->label(false) ?>
                    </div>
                </div>
            <?php } ?>
        </div>




    </div>
    <br>

    <?php if ($model->tipo_beneficiario == 0) { ?>
        <b>SELECCIONE UNA O VARIAS OPCIONES</b>
        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
            <?php
            $chequeos = [];
            if ($model->idtspersona != null) {
                $chequeos = Mds_ts_checklist::findBySql("SELECT idconfiguracion
            FROM mds_ts_checklist                                                
            WHERE idtspersona=" . $model->idtspersona)->all();
            }

            $array_asist = [];
            $m = 0;
            foreach ($chequeos as $una_asistencia) {
                $array_asist[$m] = $una_asistencia->idconfiguracion;
                $m++;
            }

            $cadcheckasist = "";
            foreach ($chequeos as $una_asistencia) {
                $cadcheckasist = $cadcheckasist . "-" . $una_asistencia->idconfiguracion;
            }
            $model->cad_check = $cadcheckasist;
            ?>
            <?= $form->field($model, 'cad_check')->hiddenInput()->label(false) ?>

            <?php
            $tipos_asistencias = Sds_com_configuracion::getConfiguraciones(94);
            $cont = 0;
            $cad_asistencias1 = '';


            foreach ($tipos_asistencias as $tipo_asist) {
                $checked = "";
                if (in_array($tipo_asist->idconfiguracion, $array_asist)) {
                    $checked = "checked";
                }

                $cad_asistencias1 = $cad_asistencias1 . '
                                <div class="col-md-6"> ' .
                    '<input type="checkbox" tabindex="1"  name="tschek" id="asistencia' . $cont . '"' .
                    ' value=' . $tipo_asist->idconfiguracion . ' ' . $checked . ' > ' . $tipo_asist->descripcion;
                $cad_asistencias1 = $cad_asistencias1 . '</div>';
                $cont++;
            }

            if ($cad_asistencias1 != '') {
                echo '<div class="row">';
                echo $cad_asistencias1;
                echo '</div>';
            }

            $model->num_opciones_asistencia = $cont;
            echo $form->field($model, 'num_opciones_asistencia')->hiddenInput(['id' => 'num_opciones_asistencia'])->label(false);
            ?>
        </div>
    <?php }  ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<  JS

function cargarLocalidades() {
    $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {             
            $("select#cmb_localidad").html(data);
    });
}
$("body").on("click","[name='tschek']",function(event)
    { 
        var cadena="";
        $("input:checkbox:checked").each(   
        function() {
            cadena=cadena+"-"+$(this).val();            
            //alert("El checkbox con valor " + $(this).val() + " está seleccionado");
        }
        );       
        $("#mds_ts_persona-cad_check").attr('value',cadena);       
    });
    

JS;

$this->registerJs($script);

?>

<?php
Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    'id' => 'modal_abm',
    'size' => 'modal-md',
]);

echo "<div id='content_abm'></div>";

Modal::end();
?>