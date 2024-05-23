<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Collapse;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\Url;
use kartik\time\TimePicker;
use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use kartik\select2\Select2;
use app\models\Sds_com_persona;
use app\models\Sds_ris_persona;
use kartik\widgets\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_atpcen_encuesta */
/* @var $form yii\widgets\ActiveForm */

function CalculaEdad($fecha)
{
    list($Y, $m, $d) = explode('-', $fecha);
    return date('md') < $m . $d ? date('Y') - $Y - 1 : date('Y') - $Y;
}
$this->title =
    ($model->isNewRecord ? 'ATPCen: Nueva ' : 'ATPCen: Editar ') . ' Encuesta ';
$this->params['breadcrumbs'][] = $this->title;
?>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="mds-atpcen-encuesta-form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <!--inicio de codigo-->
                    <?php echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_persona">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_persona" href="#persona">
                                        Datos de la Persona Encuestada
                                    </a>
                                </h4>
                            </div>
                            <div id="persona" class="accordion-body collapse in">
                                <div class="panel-body" id="persona_content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'dni_beneficiario'
                                                )
                                                ->textInput([
                                                    'id' => 'txtDNI',
                                                ]) ?>
                                            <?= $form
                                                ->field($model, 'id_risneu')
                                                ->hiddenInput([
                                                    'id' => 'hidden_id_risneu',
                                                ])
                                                ->label(false) ?>
                                        </div>
                                        <div class="col-md-3" style="padding-top:25px;">
                                            <?php echo Html::a(
                                                '<i class="glyphicon glyphicon-search"></i>',
                                                null,
                                                [
                                                    'name' => 'btn_dni',
                                                    'id' => 'btn_dni',
                                                    'data-request-method' =>
                                                        'post',
                                                    'data-toggle' => 'tooltip',
                                                    //"disabled" => $model->estado != Sds_800_llamada::ESTADO_PENDIENTE,
                                                    'class' =>
                                                        'btn btn-primary',
                                                    'title' => Yii::t(
                                                        'app',
                                                        'Consultar DNI Llamante'
                                                    ),
                                                ]
                                            ) .
                                                Html::a(
                                                    '<span>Actualizar RISNeu</span>',
                                                    null,
                                                    [
                                                        'name' => 'btn_risneu',
                                                        'id' => 'btn_risneu',
                                                        'data-request-method' =>
                                                            'post',
                                                        'data-toggle' =>
                                                            'tooltip',
                                                        'style' =>
                                                            'padding:0px;padding-left:2px;',
                                                        'class' => 'btn',
                                                    ]
                                                ); ?>
                                        </div>
                                        <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">

                                        </div>
                                        <!-- <div class="col-md-3" style="text-align: right;">
                                            <img id="renaper_foto" src="" alt="" height="75px" />
                                        </div> -->
                                    </div>
                                    <div class="row" style='display:<?= !$model->isNewRecord
                                        ? 'block'
                                        : 'none' ?> ' name="row_activo">
                                        <?php if (!$model->isNewRecord) {
                                            $un_risneu_ = Sds_ris_persona::find()
                                                ->where(
                                                    'idrisneu="' .
                                                        $model->id_risneu .
                                                        '" '
                                                )
                                                ->one();
                                            $una_com_persona = Sds_com_persona::find()
                                                ->where(
                                                    ' idpersona="' .
                                                        $un_risneu_->idpersona .
                                                        '" '
                                                )
                                                ->one();
                                            $model->nombre =
                                                $una_com_persona->nombre;
                                            $model->apellido =
                                                $una_com_persona->apellido;
                                            $model->fecha_nacimiento =
                                                $una_com_persona->fecha_nacimiento;
                                            $model->nacionalidad =
                                                $una_com_persona->nacionalidad;
                                            $model->sexo =
                                                $una_com_persona->genero;
                                        } ?>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'nombre')
                                                ->textInput([
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'apellido')
                                                ->textInput([
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php
                                            if (
                                                $model->fecha_nacimiento != null
                                            ) {
                                                $fn = $model->fecha_nacimiento;
                                                $model->fecha_nacimiento = date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        str_replace(
                                                            '/',
                                                            '-',
                                                            $model->fecha_nacimiento
                                                        )
                                                    )
                                                );
                                            } else {
                                                $fn = null;
                                            }
                                            echo $form
                                                ->field(
                                                    $model,
                                                    'fecha_nacimiento'
                                                )
                                                ->widget(
                                                    DatePicker::ClassName(),
                                                    [
                                                        'name' =>
                                                            'check_issue_date',
                                                        'language' => 'es',
                                                        'readonly' => false,
                                                        'layout' =>
                                                            '{picker}{input}{remove}',
                                                        'options' => [
                                                            'id' =>
                                                                'fecha_nacimiento',
                                                            'class' =>
                                                                'form-control input-md',
                                                            'disabled' => true,
                                                        ],
                                                        'pluginOptions' => [
                                                            'value' => null,
                                                            'format' =>
                                                                'dd/mm/yyyy',
                                                            'endDate' => date(
                                                                'd/m/Y'
                                                            ),
                                                            'todayHighlight' => true,
                                                            'autoclose' => true,
                                                        ],
                                                    ]
                                                )
                                                ->label('Fecha de Nacimiento');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row" style='display:<?= !$model->isNewRecord
                                        ? 'block'
                                        : 'none' ?> ' name="row_activo">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'nacionalidad')
                                                ->dropdownList(
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion::getConfiguraciones(
                                                            Sds_com_configuracion_tipo::TIPO_NACIONALIDAD,
                                                            false
                                                        ),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),
                                                    [
                                                        'prompt' =>
                                                            'Seleccione Nacionalidad ...',
                                                        'disabled' => 'true',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'sexo')
                                                ->dropdownList(
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion::getConfiguraciones(
                                                            Sds_com_configuracion_tipo::TIPO_GENERO,
                                                            false
                                                        ),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),
                                                    [
                                                        'prompt' =>
                                                            'Seleccione Genero ...',
                                                        'disabled' => 'true',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">

                                                <blockquote class="blockquote" id="blockedad">
                                                    <p><?php if ($fn != null) {
                                                        $edad = CalculaEdad(
                                                            $fn
                                                        );
                                                        echo 'Edad: ';
                                                        if ($edad == 1) {
                                                            echo $edad . ' año';
                                                        } else {
                                                            echo $edad .
                                                                ' años';
                                                        }
                                                    } else {
                                                        $edad = 110;
                                                    } ?></p>
                                                    <?php if ($edad < 18) {
                                                        echo '<footer class="blockquote-footer">Se requiere un tutor</footer>';
                                                    } else {
                                                        echo '<footer class="blockquote-footer">No se requiere tutor</footer>';
                                                    } ?>
                                                </blockquote>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style='display:<?= !$model->isNewRecord
                                        ? 'block'
                                        : 'none' ?> ' name="row_activo">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'telefono_contacto1'
                                                )
                                                ->textInput()
                                                ->label(
                                                    'Teléfono de Contacto'
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'telefono_contacto2'
                                                )
                                                ->textInput()
                                                ->label(
                                                    'Teléfono de Contacto 2'
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'email')
                                                ->textInput()
                                                ->label('Email') ?>
                                        </div>
                                    </div>
                                    <div class="row" style='display:<?= !$model->isNewRecord
                                        ? 'block'
                                        : 'none' ?> ' name="row_activo">
                                        <div class='col-md-6' align="center" ;>
                                            <?php if (
                                                $model->frente_dni == null
                                            ) {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'archivo_foto_dni',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(
                                                        FileInput::classname(),
                                                        [
                                                            //'name' => 'i1',
                                                            'options' => [
                                                                'accept' =>
                                                                    'image/*',
                                                            ],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                //'showPreview' => false,
                                                                'allowedFileExtensions' => [
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'gif',
                                                                    'png',
                                                                    'bmp',
                                                                ],
                                                                'showCaption' => false,
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'showCancel' => false,
                                                                'mainClass' =>
                                                                    'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/uploads/atpcen',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 1000,
                                                                /* 'initialPreview'=>[
                                                                    Html::img($model->Foto,['class'=>'file-preview-image']),
                                                                    ], */
                                                                'previewFileType' =>
                                                                    'image',
                                                                'initialCaption' =>
                                                                    $model->frente_dni,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                    'showZoom' => false,
                                                                    'showCaption' => false,
                                                                    'showCancel' => false,
                                                                ],
                                                                //'minFileCount' => 1,
                                                                // 'validateInitialCount' => true,
                                                            ],
                                                        ]
                                                    )
                                                    ->label('FRENTE DNI');
                                            } else {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'archivo_foto_dni',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(
                                                        FileInput::classname(),
                                                        [
                                                            'options' => [
                                                                'accept' =>
                                                                    'image/*',
                                                            ],
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
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'showCancel' => false,
                                                                'mainClass' =>
                                                                    'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/uploads/atpcen',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 1000,
                                                                'previewFileType' =>
                                                                    'image',
                                                                'initialPreview' => [
                                                                    //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                                                    Html::img(
                                                                        Url::base() .
                                                                            '/uploads/atpcen/' .
                                                                            $model->frente_dni,
                                                                        [
                                                                            'class' =>
                                                                                'file-preview-image',
                                                                            'style' =>
                                                                                'width:100%',
                                                                        ]
                                                                    ),
                                                                    //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->imagen);
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' =>
                                                                    $model->frente_dni,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                    'showZoom' => false,
                                                                    'showCaption' => false,
                                                                    'showCancel' => false,
                                                                ],
                                                            ],
                                                            'pluginEvents' => [
                                                                'fileclear' =>
                                                                    "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                                                'filereset' =>
                                                                    'function() {  }',
                                                            ],
                                                        ]
                                                    )
                                                    ->label('FRENTE DNI');
                                            } ?>
                                        </div>

                                        <div class='col-md-6' align="center" ;>
                                            <?php if (
                                                $model->dorso_dni == null
                                            ) {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'archivo_foto_dnidorso',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(
                                                        FileInput::classname(),
                                                        [
                                                            //'name' => 'i1',
                                                            'options' => [
                                                                'accept' =>
                                                                    'image/*',
                                                            ],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                //'showPreview' => false,
                                                                'allowedFileExtensions' => [
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'gif',
                                                                    'png',
                                                                    'bmp',
                                                                ],
                                                                'showCaption' => false,
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'showCancel' => false,
                                                                'mainClass' =>
                                                                    'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/uploads/atpcen',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 1000,
                                                                /* 'initialPreview'=>[
                                                                    Html::img($model->Foto,['class'=>'file-preview-image']),
                                                                    ], */
                                                                'previewFileType' =>
                                                                    'image',
                                                                'initialCaption' =>
                                                                    $model->dorso_dni,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                    'showZoom' => false,
                                                                    'showCaption' => false,
                                                                    'showCancel' => false,
                                                                ],
                                                                //'minFileCount' => 1,
                                                                // 'validateInitialCount' => true,
                                                            ],
                                                        ]
                                                    )
                                                    ->label('DORSO DNI');
                                            } else {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'archivo_foto_dnidorso',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(
                                                        FileInput::classname(),
                                                        [
                                                            'options' => [
                                                                'accept' =>
                                                                    'image/*',
                                                            ],
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
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'showCancel' => false,
                                                                'mainClass' =>
                                                                    'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/uploads/atpcen',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 1000,
                                                                'previewFileType' =>
                                                                    'image',
                                                                'initialPreview' => [
                                                                    //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                                                    Html::img(
                                                                        Url::base() .
                                                                            '/uploads/atpcen/' .
                                                                            $model->dorso_dni,
                                                                        [
                                                                            'class' =>
                                                                                'file-preview-image',
                                                                            'style' =>
                                                                                'width:100%',
                                                                        ]
                                                                    ),
                                                                    //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->imagen);
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' =>
                                                                    $model->dorso_dni,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                    'showZoom' => false,
                                                                    'showCaption' => false,
                                                                    'showCancel' => false,
                                                                ],
                                                            ],
                                                            'pluginEvents' => [
                                                                'fileclear' =>
                                                                    "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                                                'filereset' =>
                                                                    'function() {  }',
                                                            ],
                                                        ]
                                                    )
                                                    ->label('DORSO DNI');
                                            } ?>
                                        </div>
                                    </div>

                                    <?php
//$form->field($model, 'idpersona')->hiddenInput()->label(false)
?>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="panel-group" id="accordion_intervencion" style='display:<?= !$model->isNewRecord
                        ? 'block'
                        : 'none' ?> ' name="row_activo">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                        Datos de la Entrevista
                                    </a>
                                </h4>
                            </div>
                            <div id="intervencion" class="accordion-body collapse in">
                                <div class="panel-body" id="intervencion_content">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <?php
                                            if (
                                                $model->fecha_hora_entrevista !=
                                                null
                                            ) {
                                                $model->fecha_hora_entrevista = date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        str_replace(
                                                            '/',
                                                            '-',
                                                            $model->fecha_hora_entrevista
                                                        )
                                                    )
                                                );
                                            }
                                            echo $form
                                                ->field(
                                                    $model,
                                                    'fecha_hora_entrevista'
                                                )
                                                ->widget(
                                                    DatePicker::ClassName(),
                                                    [
                                                        'name' =>
                                                            'check_issue_date_desde',
                                                        'language' => 'es',
                                                        'readonly' => false,

                                                        // 'layout' => '{picker}{input}{remove}',
                                                        'layout' => !$model->isNewRecord
                                                            ? '{picker}{input}'
                                                            : '{picker}{input}',

                                                        'pluginOptions' => [
                                                            'value' => null,
                                                            'format' =>
                                                                'dd/mm/yyyy',
                                                            //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                                                            'todayHighlight' => true,
                                                            'autoclose' => true,
                                                        ],
                                                    ]
                                                )
                                                ->label(
                                                    'Fecha de la Entrevista'
                                                );
                                            ?>
                                        </div>
                                        <div class="col-md-2">
                                            <?php echo $form
                                                ->field(
                                                    $model,
                                                    'hora_entrevista'
                                                )
                                                ->widget(
                                                    TimePicker::classname(),
                                                    [
                                                        //'options' => ['value' =>'00:00'],
                                                        'options' => [
                                                            'id' =>
                                                                'hora_entrevista',
                                                            'disabled' => false,
                                                            'class' =>
                                                                'form-control input-sm',
                                                        ],
                                                        'pluginOptions' => [
                                                            'showSeconds' => true,
                                                            'showMeridian' => false,
                                                            'minuteStep' => 1,
                                                            'secondStep' => 1,
                                                        ],
                                                    ]
                                                ); ?>
                                        </div>
                                        <div class="col-md-3">

                                            <?php if (
                                                $model->id_localidad_entrevista ==
                                                ''
                                            ) {
                                            } else {
                                                //echo 'el id localidad es: '.$model->id_localidad;
                                                $una_prov = Sds_com_localidad::find()
                                                    ->select(['idprovincia'])
                                                    ->where([
                                                        'idlocalidad' =>
                                                            $model->id_localidad_entrevista,
                                                    ])
                                                    ->one();
                                                $model->la_provincia =
                                                    $una_prov->idprovincia;
                                            } ?>
                                            <?= $form
                                                ->field($model, 'la_provincia')
                                                ->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(
                                                        Sds_com_provincia::find()
                                                            ->orderBy([
                                                                'descripcion' => SORT_ASC,
                                                            ])
                                                            ->all(),
                                                        'idprovincia',
                                                        'descripcion'
                                                    ),
                                                    'options' => [
                                                        'placeholder' =>
                                                            'Seleccione Provincia ...',
                                                        'id' => 'cmb_provincia',
                                                        'onchange' =>
                                                            'cargarLocalidades();',
                                                    ],

                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                    ],
                                                ])
                                                ->label('Provincia') ?>

                                        </div>
                                        <div class="col-md-3">

                                            <?php if (
                                                $model->id_localidad_entrevista ==
                                                ''
                                            ) {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'id_localidad_entrevista'
                                                    )
                                                    ->widget(
                                                        Select2::classname(),
                                                        [
                                                            /*'data' => ArrayHelper::map(
                                                                        Mds_rum_localidad::find()->orderBy(['nombre' => SORT_ASC])->all(),
                                                                        'id',
                                                                        'nombre'
                                                                    ),*/
                                                            'options' => [
                                                                'placeholder' =>
                                                                    'Seleccione ...',
                                                                'id' =>
                                                                    'cmb_localidad',
                                                            ],

                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ]
                                                    )
                                                    ->label('Localidad');
                                            } else {
                                                //echo 'el id localidad es: '.$model->id_localidad;
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'id_localidad_entrevista'
                                                    )
                                                    ->widget(
                                                        Select2::classname(),
                                                        [
                                                            'data' => ArrayHelper::map(
                                                                Sds_com_localidad::find()
                                                                    ->where([
                                                                        'idprovincia' =>
                                                                            $una_prov->idprovincia,
                                                                    ])
                                                                    ->orderBy([
                                                                        'descripcion' => SORT_ASC,
                                                                    ])
                                                                    ->all(),
                                                                'idlocalidad',
                                                                'descripcion'
                                                            ),
                                                            'options' => [
                                                                'placeholder' =>
                                                                    'Seleccione ...',
                                                                'id' =>
                                                                    'cmb_localidad',
                                                            ],

                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ]
                                                    )
                                                    ->label('Localidad');
                                            } ?>

                                        </div>
                                        <div class="col-md-2">
                                            <?= $form
                                                ->field($model, 'entrevistador')
                                                ->textInput()
                                                ->label('Entrevistador') ?>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                        </div>
                                        <div class="col-md-6">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <?php
/*if ($edad<18){echo ' style="display:block ;" ';} else {echo 'style="display:none"';}*/
?>

                    <div class="panel-group" id="accordion_intervencion" name="div_tutor" style='display:<?= !$model->isNewRecord
                        ? 'block'
                        : 'none' ?> '>
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                        Datos del Tutor
                                    </a>
                                </h4>
                            </div>
                            <div id="intervencion" class="accordion-body collapse in">
                                <div class="panel-body" id="intervencion_content">
                                    <div class="row">
                                        <div class="col-md-3">

                                            <?php echo $form
                                                ->field(
                                                    $model,
                                                    'tipo_documento_tutor'
                                                )
                                                ->dropDownList(
                                                    [
                                                        'DNI' => 'DNI',
                                                        'LC' => 'LC',
                                                        'LE' => 'LE',
                                                        'CI' => 'CI',
                                                        'PASAPORTE EXTRANJERO' =>
                                                            'PASAPORTE EXTRANJERO',
                                                        'CEDULA DE IDENTIDAD EXTRANJERA' =>
                                                            'CEDULA DE IDENTIDAD EXTRANJERA',
                                                        ' NO TIENE' =>
                                                            ' NO TIENE',
                                                        'OTRO' => 'OTRO',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                ); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'documento_tutor'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'cuil_tutor')
                                                ->textInput([
                                                    'maxlength' => true,
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo $form
                                                ->field($model, 'sexo_tutor')
                                                ->dropDownList(
                                                    [
                                                        'F' => 'Femenino',
                                                        'M' => 'Masculino',
                                                        'I' => 'Indefinido',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                ); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'nombre_tutor')
                                                ->textInput([
                                                    'maxlength' => true,
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'apellido_tutor'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            if (
                                                $model->fecha_nac_tutor != null
                                            ) {
                                                $model->fecha_nac_tutor = date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        str_replace(
                                                            '/',
                                                            '-',
                                                            $model->fecha_nac_tutor
                                                        )
                                                    )
                                                );
                                            }
                                            echo $form
                                                ->field(
                                                    $model,
                                                    'fecha_nac_tutor'
                                                )
                                                ->widget(
                                                    DatePicker::ClassName(),
                                                    [
                                                        'name' =>
                                                            'check_issue_date',
                                                        'language' => 'es',
                                                        'readonly' => false,
                                                        'layout' =>
                                                            '{picker}{input}{remove}',
                                                        'options' => [
                                                            'id' =>
                                                                'fecha_nac_tutor',
                                                            'class' =>
                                                                'form-control input-md',
                                                            'disabled' => false,
                                                        ],
                                                        'pluginOptions' => [
                                                            'value' => null,
                                                            'format' =>
                                                                'dd/mm/yyyy',
                                                            'endDate' => date(
                                                                'd/m/Y'
                                                            ),
                                                            'todayHighlight' => true,
                                                            'autoclose' => true,
                                                        ],
                                                    ]
                                                )
                                                ->label(
                                                    'Fecha Nacimiento Tutor'
                                                );
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'parentezco_tutor'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                ]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                        </div>
                                        <div class="col-md-6">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="panel-group" id="accordion_intervencion" style='display:<?= !$model->isNewRecord
                        ? 'block'
                        : 'none' ?> ' name="row_activo">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                        Ubicación del núcleo de convivencia (Situación Habitacional)
                                    </a>
                                </h4>
                            </div>
                            <div id="intervencion" class="accordion-body collapse in">
                                <div class="panel-body" id="intervencion_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            Condiciones de habitabilidad(Suficiente o insuficiente para el número de integrantes)
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php echo $form
                                                ->field($model, 'condiciones')
                                                ->dropDownList(
                                                    [
                                                        'suficiente' =>
                                                            'Suficiente',
                                                        'insuficiente' =>
                                                            'Insuficiente',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                )
                                                ->label(''); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" id="accordion_intervencion" style='display:<?= !$model->isNewRecord
                        ? 'block'
                        : 'none' ?> ' name="row_activo">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                        Salud de la persona con celiaquia
                                    </a>
                                </h4>
                            </div>
                            <div id="intervencion" class="accordion-body collapse in">
                                <div class="panel-body" id="intervencion_content">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <?php echo $form
                                                ->field(
                                                    $model,
                                                    'tiene_obra_social'
                                                )
                                                ->dropDownList(
                                                    ['1' => 'Si', '0' => 'No'],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Tiene obra social?'
                                                ); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'obra_social')
                                                ->textInput([
                                                    'maxlength' => true,
                                                ])
                                                ->label('Cual?') ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo $form
                                                ->field(
                                                    $model,
                                                    'id_establ_salud'
                                                )
                                                ->dropDownList(
                                                    [
                                                        '0' =>
                                                            'Hospital Público',
                                                        '1' =>
                                                            'Centro de salud',
                                                        '2' => 'Ambito privado',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                )
                                                ->label(
                                                    'Establecimiento al que concurre'
                                                ); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'establecimiento_salud'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                ])
                                                ->label('Cual?') ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <?php echo $form
                                                ->field($model, 'tiene_biopsia')
                                                ->dropDownList(
                                                    ['1' => 'Si', '0' => 'No'],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                )
                                                ->label('¿Tiene biopsia?'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            if (
                                                $model->fecha_diagnostico !=
                                                null
                                            ) {
                                                $model->fecha_diagnostico = date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        str_replace(
                                                            '/',
                                                            '-',
                                                            $model->fecha_diagnostico
                                                        )
                                                    )
                                                );
                                            }
                                            echo $form
                                                ->field(
                                                    $model,
                                                    'fecha_diagnostico'
                                                )
                                                ->widget(
                                                    DatePicker::ClassName(),
                                                    [
                                                        'name' =>
                                                            'check_issue_date',
                                                        'language' => 'es',
                                                        'readonly' => false,
                                                        'layout' =>
                                                            '{picker}{input}{remove}',
                                                        'options' => [
                                                            'id' =>
                                                                'fecha_diagnostico',
                                                            'class' =>
                                                                'form-control input-md',
                                                            'disabled' => false,
                                                        ],
                                                        'pluginOptions' => [
                                                            'value' => null,
                                                            'format' =>
                                                                'dd/mm/yyyy',
                                                            'endDate' => date(
                                                                'd/m/Y'
                                                            ),
                                                            'todayHighlight' => true,
                                                            'autoclose' => true,
                                                        ],
                                                    ]
                                                )
                                                ->label(
                                                    'Fecha del Diagnóstico'
                                                );
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo $form
                                                ->field(
                                                    $model,
                                                    'concurre_a_control'
                                                )
                                                ->dropDownList(
                                                    ['1' => 'Si', '0' => 'No'],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Concurre a control médico?'
                                                ); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo $form
                                                ->field($model, 'frecuencia')
                                                ->dropDownList(
                                                    [
                                                        '0' => 'anual',
                                                        '1' => 'cada 2-3 años',
                                                        '2' =>
                                                            'cada 3 años o más',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Con que frecuencia?'
                                                ); ?>
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            ¿En el grupo familia, hay algun otro/a integrante con enfermedad celiaca?
                                        </div>
                                        <div class="col-md-2">
                                            <?php echo $form
                                                ->field(
                                                    $model,
                                                    'integrante_celiaco'
                                                )
                                                ->dropDownList(
                                                    ['1' => 'Si', '0' => 'No'],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                )
                                                ->label(false); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-md-6' align="center" ;>
                                            <?php if (
                                                $model->estudio_biopsia == null
                                            ) {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'archivo_biopsia',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(
                                                        FileInput::classname(),
                                                        [
                                                            //'name' => 'i1',
                                                            'options' => [
                                                                'accept' =>
                                                                    'image/*',
                                                            ],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                //'showPreview' => false,
                                                                'allowedFileExtensions' => [
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'gif',
                                                                    'png',
                                                                    'bmp',
                                                                ],
                                                                'showCaption' => false,
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'showCancel' => false,
                                                                'mainClass' =>
                                                                    'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/uploads/atpcen',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 1000,
                                                                /* 'initialPreview'=>[
                                                                    Html::img($model->Foto,['class'=>'file-preview-image']),
                                                                    ], */
                                                                'previewFileType' =>
                                                                    'image',
                                                                'initialCaption' =>
                                                                    $model->estudio_biopsia,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                    'showZoom' => false,
                                                                    'showCaption' => false,
                                                                    'showCancel' => false,
                                                                ],
                                                                //'minFileCount' => 1,
                                                                // 'validateInitialCount' => true,
                                                            ],
                                                        ]
                                                    )
                                                    ->label('IMAGEN BIOPSIA');
                                            } else {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'archivo_biopsia',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(
                                                        FileInput::classname(),
                                                        [
                                                            'options' => [
                                                                'accept' =>
                                                                    'image/*',
                                                            ],
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
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'showCancel' => false,
                                                                'mainClass' =>
                                                                    'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/uploads/atpcen',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 1000,
                                                                'previewFileType' =>
                                                                    'image',
                                                                'initialPreview' => [
                                                                    //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                                                    Html::img(
                                                                        Url::base() .
                                                                            '/uploads/atpcen/' .
                                                                            $model->estudio_biopsia,
                                                                        [
                                                                            'class' =>
                                                                                'file-preview-image',
                                                                            'style' =>
                                                                                'width:100%',
                                                                        ]
                                                                    ),
                                                                    //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->imagen);
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' =>
                                                                    $model->estudio_biopsia,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                    'showZoom' => false,
                                                                    'showCaption' => false,
                                                                    'showCancel' => false,
                                                                ],
                                                            ],
                                                            'pluginEvents' => [
                                                                'fileclear' =>
                                                                    "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                                                'filereset' =>
                                                                    'function() {  }',
                                                            ],
                                                        ]
                                                    )
                                                    ->label('IMAGEN BIOPSIA');
                                            } ?>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="panel-group" id="accordion_intervencion" style='display:<?= !$model->isNewRecord
                    ? 'block'
                    : 'none' ?> ' name="row_activo">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                    En relación a la alimentación
                                </a>
                            </h4>
                        </div>
                        <div id="intervencion" class="accordion-body collapse in">
                            <div class="panel-body" id="intervencion_content">


                                <div class="row">
                                    <div class="col-md-3">
                                        <?php echo $form
                                            ->field($model, 'tarjeta_atpcen')
                                            ->dropDownList(
                                                ['1' => 'Si', '0' => 'No'],
                                                [
                                                    'prompt' =>
                                                        'Seleccione ...',
                                                ]
                                            )
                                            ->label(
                                                '¿Tiene tarjeta para celiacos (ATPCen)?'
                                            ); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo $form
                                            ->field($model, 'modulo_alimento')
                                            ->dropDownList(
                                                ['1' => 'Si', '0' => 'No'],
                                                [
                                                    'prompt' =>
                                                        'Seleccione ...',
                                                ]
                                            )
                                            ->label(
                                                '¿Recibe módulos de alimentos?'
                                            ); ?>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form
                                            ->field($model, 'organismo_asiste')
                                            ->textInput(['maxlength' => true])
                                            ->label(
                                                'Organismo que lo/a asiste'
                                            ) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form
                                            ->field(
                                                $model,
                                                'cantidad_asistencia'
                                            )
                                            ->textInput(['maxlength' => true])
                                            ->label('Cantidad') ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $form
                                            ->field(
                                                $model,
                                                'periocidad_asistencia'
                                            )
                                            ->dropDownList(
                                                [
                                                    '0' => 'diario',
                                                    '1' => 'semanal',
                                                    '2' => 'mensual',
                                                    '3' => 'bimensual',
                                                    '4' => 'semestral',
                                                    '5' => 'anual',
                                                    '6' => 'otro',
                                                ],
                                                [
                                                    'prompt' =>
                                                        'Seleccione ...',
                                                ]
                                            )
                                            ->label(
                                                '¿Con que periodicidad?'
                                            ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="panel-group" id="accordion_intervencion" style='display:<?= !$model->isNewRecord
                    ? 'block'
                    : 'none' ?> ' name="row_activo">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                    Capacitación/talleres
                                </a>
                            </h4>
                        </div>
                        <div id="intervencion" class="accordion-body collapse in">
                            <div class="panel-body" id="intervencion_content">


                                <div class="row">
                                    <div class="col-md-8">
                                        En relación a la salud y alimentación: ¿Le interesa recibir información sobre alimentación sana libre de TACC, distintos usos de los alimentos secos y frescos, preparación de la huerta familiar o comunitaria?
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $form
                                            ->field(
                                                $model,
                                                'interes_capacitacion'
                                            )
                                            ->dropDownList(
                                                ['1' => 'Si', '0' => 'No'],
                                                [
                                                    'prompt' =>
                                                        'Seleccione ...',
                                                ]
                                            )
                                            ->label(false); ?>
                                    </div>
                                </div>
                                <div class="row"> <br>
                                    <div class="col-md-8">
                                        <?= $form
                                            ->field(
                                                $model,
                                                'capacitacion_solicitada'
                                            )
                                            ->textarea(['rows' => 6]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="panel-group" id="accordion_intervencion" style='display:<?= !$model->isNewRecord
                        ? 'block'
                        : 'none' ?> ' name="row_activo">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                        Observaciones
                                    </a>
                                </h4>
                            </div>
                            <div id="intervencion" class="accordion-body collapse in">
                                <div class="panel-body" id="intervencion_content">
                                    <div class="row">
                                        <div class="col-md-8">
                                            Considera que el titular del beneficio se encuentra en situación de vulnerabilidad social?:
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <?php echo $form
                                                ->field(
                                                    $model,
                                                    'vulnerabilidad_social'
                                                )
                                                ->dropDownList(
                                                    ['1' => 'Si', '0' => 'No'],
                                                    [
                                                        'prompt' =>
                                                            'Seleccione ...',
                                                    ]
                                                )
                                                ->label(false); ?>
                                        </div>
                                    </div>
                                    <div class="row"> <br>
                                        <div class="col-md-8">
                                            <?= $form
                                                ->field($model, 'observacion')
                                                ->textarea(['rows' => 6])
                                                ->label(false) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>





                        <!-- fin de codigo-->
                    </div>
            </section>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="row" style="padding-top: 2%">
            <div class="col-md-1">
                <!--<a class="btn btn-info" href="javascript:history.back(1)">Volver </a>-->
                <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
            </div>
            <div class="col-md-3 col-md-offset-8">
                <div class="form-group" style='display:<?= !$model->isNewRecord
                    ? 'block'
                    : 'none' ?> ' name="row_activo">

                    <?= Html::submitButton(
                        $model->isNewRecord ? 'Guardar' : 'Guardar',
                        [
                            'class' => $model->isNewRecord
                                ? 'btn btn-success'
                                : 'btn btn-primary',
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJs(
    "
    $('#btn_risneu').hide();
    $('#btn_dni').click(function(){        
        datos_persona(false);
        
    });
    $('#btn_risneu').click(function(){ 
        var dni = $('#txtDNI').val();       
        var llamada = $('#sds_800_atencion_am-idllamada').val()
        getIdRisneu(dni);
    });

    "
); ?>
<script>
    function getIdRisneu(dni) {
        $.post("index.php?r=mds_atpcen_encuesta/get_id_risneu&dni=" + dni, function(data) {
            data = $.parseJSON(data);
            window.open('<?php echo Url::base(); ?>/index.php?r=sds_ris_risneu%2Fupdate&finalizar=0&dni=' + dni + '&id=' + data, '_blank');
        });
    }

    function calculate_age(birth_month, birth_day, birth_year) {
        today_date = new Date();
        today_year = today_date.getFullYear();
        today_month = today_date.getMonth();
        today_day = today_date.getDate();
        age = today_year - birth_year;

        if (today_month < (birth_month - 1)) {
            age--;
        }
        if (((birth_month - 1) == today_month) && (today_day < birth_day)) {
            age--;
        }
        return age;
    }

    function actualizardatos() {
        fechanac = document.getElementById("fecha_nacimiento").value;
        0
        cadfecha = fechanac.split("/");
        edad = calculate_age(cadfecha[1], cadfecha[0], cadfecha[2])
        if (edad < 18) {
            if (edad == 1) {
                cad = "<p>Edad: " + edad + " año </p> <footer class=\"blockquote-footer\">Se requiere un tutor</footer>";
            } else {
                cad = "<p>Edad: " + edad + " años </p> <footer class=\"blockquote-footer\">Se requiere un tutor</footer>";
            }

            $("[name='div_tutor']").show();
        } else {
            cad = "<p>Edad: " + edad + " años </p> <footer class=\"blockquote-footer\">No se requiere tutor</footer>";
            $("[name='div_tutor']").hide();
        }
        document.getElementById("blockedad").innerHTML = cad;



    }

    function habilitar_controles() {
        $("[name='row_activo']").show();

    }

    function deshabilitar_controles() {
        $("[name='row_activo']").hide();
        $("[name='div_tutor']").hide();
        $("[name='btn_risneu']").hide();

    }

    function datos_persona(primera_vez = false) {
        deshabilitar_controles();
        var dni_campo = $('#txtDNI').val();
        //if (dni != dni_campo || primera_vez) {
        if (dni_campo != '') {
            $('#txt_mensaje').html("Buscando datos de Persona...");
            dni = dni_campo;
            $.post("index.php?r=mds_atpcen_encuesta/validar_dni&dni=" + dni_campo, function(data) {
                data = $.parseJSON(data);
                console.log(data);
                if (data.length === 0) {
                    datos_renaper(dni);
                } else {
                    $("#mds_atpcen_encuesta-nombre").val(data[1]['nombre']);
                    $("#mds_atpcen_encuesta-apellido").val(data[1]['apellido']);
                    $("#fecha_nacimiento").val(data[1]['fecha_nacimiento']);
                    $("#mds_atpcen_encuesta-nacionalidad").val(data[1]['nacionalidad']);
                    $("#mds_atpcen_encuesta-sexo").val(data[1]['genero']);
                    $("#hidden_id_risneu").val(data[0]['idrisneu']);
                    actualizardatos();
                    /*
                    if (data.length > 1) {
                        $("#sds_800_llamada-telefono").val(data[1]['telefono']);
                        $("#sds_800_llamada-domicilio").val(data[1]['domicilio']);
                        $("#sds_800_llamada-localidad").val(data[1]['idlocalidad']);
                    } else {
                        $("#sds_800_llamada-telefono").val("");
                        $("#sds_800_llamada-domicilio").val("");
                        $("#sds_800_llamada-localidad").val("");
                    }*/
                    $('#txt_mensaje').html("");
                    if (data.length > 0) {
                        $("#btn_risneu").show();
                    } else {
                        $("#btn_risneu").hide();
                        $("#btn_risneu").prop("href", "");
                    }
                    //$("#renaper_foto").attr("src", '');
                    habilitar_controles();
                }
            });
        } else {
            alert('dni vacio');
        }
        //}
    }

    function cargarLocalidades() {
        $.post('index.php?r=sds_com_localidad/cmb_localidad&idprovincia=' + $('#cmb_provincia').val(), function(data) {
            $('select#cmb_localidad').html(data);
        });
    }
</script>