<?php

use app\models\Mds_org_documento;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_entrega;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

use app\models\Sds_ent_tipo;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use kartik\widgets\FileInput;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_entrega */
/* @var $form yii\widgets\ActiveForm */

if (!isset($exito)) {
    $exito = false;
}

$this->title = $model->isNewRecord
    ? 'Nueva Entrega'
    : 'Modificar Entrega N°' . $model->identrega;
?>
<style>
    .content-body {
        padding-top: 20px;
        padding-bottom: 0px;
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li>
                <a href="index.php?r=sds_ent_entrega&estado=2">
                    Entregas Finales
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="sds-ent-entrega-form">
                    <?php if ($exito) : ?>
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4><i class="icon fa fa-check"></i> Entrega Guardada correctamente!
                                <br> Continúe para agregar una nueva o presione el botón volver para ir al listado de entregas
                            </h4>
                        </div>
                    <?php endif; ?>
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <!--
                            <div class="col-md-3 col-md-offset-9" style="text-align:right">
                                <div class="form-group">
                                    <a class="btn btn-info" href="index.php?r=sds_ent_entrega&estado=2">Volver </a>
                                </div>
                            </div>
                            -->
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($model, 'dni')->textInput([
                                        'id' => 'txtDNI',
                                        'disabled' =>
                                        $model->idsolicitud != null,
                                        'tabIndex' => '1',
                                    ]) ?>
                                </div>
                                <div class="col-md-2" style="padding-top:25px;">
                                    <?php echo Html::a(
                                        '<i class="glyphicon glyphicon-search"></i>',
                                        null,
                                        [
                                            'name' => 'btn_dni',
                                            'id' => 'btn_dni',
                                            'data-request-method' => 'post',
                                            'data-toggle' => 'tooltip',
                                            'class' => 'btn btn-primary',
                                            'title' => Yii::t(
                                                'app',
                                                'Consultar DNI Persona'
                                            ),
                                        ]
                                    ); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $fecha_hora = $model->fecha_hora;
                                    $model->hora = date(
                                        'H:i',
                                        strtotime(
                                            str_replace(
                                                '/',
                                                '-',
                                                $model->fecha_hora
                                            )
                                        )
                                    );
                                    $model->fecha_hora = date(
                                        'd/m/Y',
                                        strtotime(
                                            str_replace(
                                                '/',
                                                '-',
                                                $model->fecha_hora
                                            )
                                        )
                                    );
                                    echo $form
                                        ->field($model, 'fecha_hora')
                                        ->widget(DatePicker::ClassName(), [
                                            'name' => 'check_issue_date',
                                            'language' => 'es',
                                            'readonly' => false,
                                            'layout' =>
                                            '{picker}{input}{remove}',
                                            'options' => [
                                                'id' => 'fecha_entrega',
                                                'tabIndex' => '0',
                                                'class' =>
                                                'form-control input-md',
                                                'disabled' => true,
                                            ],
                                            'pluginOptions' => [
                                                'value' => null,
                                                'format' => 'dd/mm/yyyy',
                                                'endDate' => date('d/m/Y'),
                                                'todayHighlight' => true,
                                                'autoclose' => true,
                                            ],
                                        ])
                                        ->label('Fecha (dd/mm/yyyy)');
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form
                                        ->field($model, 'hora')
                                        ->widget(TimePicker::classname(), [
                                            //'options' => ['value' =>'00:00'],
                                            'options' => [
                                                'id' => 'hora',
                                                'tabIndex' => '0',
                                                'class' =>
                                                'form-control input-sm',
                                                'disabled' => true,
                                            ],
                                            'pluginOptions' => [
                                                'showSeconds' => false,
                                                'showMeridian' => false,
                                                'minuteStep' => 15,
                                                //'secondStep' => 5,
                                            ],
                                        ]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form
                                        ->field($model, 'idtipo')
                                        ->dropDownList(
                                            ArrayHelper::map(
                                                Sds_ent_tipo::find()
                                                    ->where(
                                                        'idtipo in (select idtipo from mds_seg_usuario_entrega_tipo ut where ut.idusuario=' .
                                                            $model->idusuario .
                                                            ')'
                                                    )
                                                    ->orderBy([
                                                        'descripcion' => SORT_ASC,
                                                    ])
                                                    ->all(),
                                                'idtipo',
                                                'descripcion'
                                            ),
                                            [
                                                'prompt' =>
                                                'Seleccionar Tipo Entrega ...',
                                                'id' => 'cmb_tipo',
                                                'tabIndex' => '1',
                                                'disabled' => true,
                                            ]
                                        ) ?>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <?= $form
                                            ->field($model, 'emisor')
                                            ->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map(
                                                    Sds_ent_entrega::find()
                                                        ->leftJoin('sds_com_configuracion c','c.idconfiguracion=receptor')
                                                        ->where('idconfiguracion is not null and receptor is not null')
                                                        ->orderBy('')
                                                        ->all(),
                                                    'identrega',
                                                    function ($model) {
                                                        return $model->toString();
                                                    }
                                                ),
                                                'options' => [
                                                    'placeholder' =>
                                                    'Seleccionar Emisor ...',
                                                    'id' => 'cmb_emisor',
                                                    'tabIndex' => '1',
                                                    'disabled' => true,
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => false,
                                                ],
                                            ]) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form
                                        ->field($model, 'saldo')
                                        ->textInput([
                                            'type' => 'number',
                                            'readOnly' => true,
                                        ]) ?>
                                </div>
                                <div id="num_container" class="col-md-4" style="display:none;">
                                    <?= $form
                                        ->field($model, 'numero')
                                        ->textInput([
                                            'tabIndex' => '1',
                                            'disabled' => true,
                                        ]) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= $form
                                        ->field($model, 'cantidad')
                                        ->textInput([
                                            'type' => 'number',
                                            'tabIndex' => '1',
                                            'disabled' => true,
                                        ]) ?>
                                </div>
                                <div class="col-md-3" style="padding-top: 33px;">
                                    <?= $form->field($model, 'interior')
                                    ->checkbox(['checked' => 
                                    ($model->isNewRecord ? false : ($model->interior ? true : false))]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php echo Collapse::widget([]);
                            //Trampita para que anden los accordion del template con yii ;)
                            ?>
                            <div class="panel-group" id="accordion_renaper">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#renaper">
                                                Datos de la persona
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="renaper" class="accordion-body collapse in">
                                        <div class="panel-body" id="renaper_content">
                                            <div class="row">
                                                <!-- <div class="col-md-3" style="text-align: center;">
                                                    <img id="renaper_foto" src="" alt="" width="100%" />
                                                </div> -->
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12" style="text-align: center;" id="txt_mensaje"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <?= $form
                                                                ->field(
                                                                    $model,
                                                                    'nombre'
                                                                )
                                                                ->textInput([
                                                                    'disabled' =>
                                                                    'true',
                                                                    'tabIndex' =>
                                                                    '1',
                                                                ]) ?>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <?= $form
                                                                ->field(
                                                                    $model,
                                                                    'apellido'
                                                                )
                                                                ->textInput([
                                                                    'disabled' =>
                                                                    'true',
                                                                    'tabIndex' =>
                                                                    '1',
                                                                ]) ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <?php
                                                            if (
                                                                $model->fecha_nacimiento !=
                                                                null
                                                            ) {
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
                                                                        'language' =>
                                                                        'es',
                                                                        'readonly' => false,
                                                                        'layout' =>
                                                                        '{picker}{input}{remove}',
                                                                        'options' => [
                                                                            'id' =>
                                                                            'fecha_nacimiento',
                                                                            'tabIndex' =>
                                                                            '1',
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
                                                                ->label(
                                                                    'Fecha de Nacimiento'
                                                                );
                                                            ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <?= $form
                                                                ->field(
                                                                    $model,
                                                                    'sexo'
                                                                )
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
                                                                        'Seleccionar Genero ...',
                                                                        'tabIndex' =>
                                                                        '1',
                                                                        'disabled' =>
                                                                        'true',
                                                                    ]
                                                                ) ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?= $form
                                                                ->field(
                                                                    $model,
                                                                    'nacionalidad'
                                                                )
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
                                                                        'Seleccionar Nacionalidad ...',
                                                                        'tabIndex' =>
                                                                        '1',
                                                                        'disabled' =>
                                                                        'true',
                                                                    ]
                                                                ) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel-group" id="accordion_observaciones">
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#observaciones">
                                                        Observaciones
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="observaciones" class="accordion-body collapse" aria-expanded="false">
                                                <div class="panel-body" id="observaciones_content">
                                                    <?= $form
                                                        ->field(
                                                            $model,
                                                            'observaciones'
                                                        )
                                                        ->textarea([
                                                            'rows' => '15',
                                                            'disabled' => true,
                                                            'tabIndex' => '1',
                                                        ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-group" id="accordion_adjunto_dni">
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#adjunto_dni">
                                                        Archivos DNI
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="adjunto_dni" class="accordion-body collapse" aria-expanded="false">
                                                <div class="panel-body" id="adjunto_dni_content">
                                                    <div class="col-md-6" style="text-align:center;">
                                                        <div class='col-md-12'>
                                                            <?php if (
                                                                $model->dni_frente ==
                                                                null
                                                            ) {
                                                                echo $form
                                                                    ->field(
                                                                        $model,
                                                                        'archivo_dni_frente',
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
                                                                            'language' =>
                                                                            'es',
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
                                                                                'mainClass' =>
                                                                                'input-group-sm',
                                                                                'uploadUrl' => Url::to(
                                                                                    [
                                                                                        '/sds_ent_entrega/update',
                                                                                    ]
                                                                                ),
                                                                                'maxFileSize' => 3000,
                                                                                /* 'initialPreview'=>[
                                                                            Html::img($model->Foto,['class'=>'file-preview-image']),
                                                                            ], */
                                                                                'previewFileType' =>
                                                                                'image',
                                                                                'initialCaption' =>
                                                                                $model->dni_frente,
                                                                                'fileActionSettings' => [
                                                                                    'showRemove' => true,
                                                                                    'showUpload' => false,
                                                                                ],
                                                                                //'minFileCount' => 1,
                                                                                // 'validateInitialCount' => true,
                                                                            ],
                                                                        ]
                                                                    )
                                                                    ->label(
                                                                        'DNI FRENTE'
                                                                    );
                                                            } else {
                                                                echo $form
                                                                    ->field(
                                                                        $model,
                                                                        'archivo_dni_frente',
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
                                                                            'language' =>
                                                                            'es',
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
                                                                                'mainClass' =>
                                                                                'input-group-sm',
                                                                                'uploadUrl' => Url::to(
                                                                                    [
                                                                                        '/sds_ent_entrega/update',
                                                                                    ]
                                                                                ),
                                                                                'maxFileSize' => 3000,
                                                                                'previewFileType' =>
                                                                                'image',
                                                                                'initialPreview' => [
                                                                                    Html::img(
                                                                                        $model->dni_frente,
                                                                                        [
                                                                                            'class' =>
                                                                                            'file-preview-image',
                                                                                            'style' =>
                                                                                            'width:100%',
                                                                                        ]
                                                                                    ),
                                                                                ],
                                                                                'overwriteInitial' => true,
                                                                                'autoReplace' => true,
                                                                                'initialCaption' =>
                                                                                $model->dni_frente,
                                                                                'fileActionSettings' => [
                                                                                    'showRemove' => false,
                                                                                    'showUpload' => false,
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
                                                                    ->label(
                                                                        'DNI FRENTE'
                                                                    );
                                                            } ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class='col-md-12'>
                                                            <?php if (
                                                                $model->dni_dorso ==
                                                                null
                                                            ) {
                                                                echo $form
                                                                    ->field(
                                                                        $model,
                                                                        'archivo_dni_dorso',
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
                                                                            'language' =>
                                                                            'es',
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
                                                                                'mainClass' =>
                                                                                'input-group-sm',
                                                                                'uploadUrl' => Url::to(
                                                                                    [
                                                                                        '/sds_ent_entrega/update',
                                                                                    ]
                                                                                ),
                                                                                'previewFileType' =>
                                                                                'image',
                                                                                'maxFileSize' => 3000,
                                                                                /* 'initialPreview'=>[
                                                                                Html::img($model->Foto,['class'=>'file-preview-image']),
                                                                                ], */
                                                                                'initialCaption' =>
                                                                                $model->dni_frente,
                                                                                'fileActionSettings' => [
                                                                                    'showRemove' => true,
                                                                                    'showUpload' => false,
                                                                                ],
                                                                                //'minFileCount' => 1,
                                                                                // 'validateInitialCount' => true,
                                                                            ],
                                                                        ]
                                                                    )
                                                                    ->label(
                                                                        'DNI DORSO'
                                                                    );
                                                            } else {
                                                                echo $form
                                                                    ->field(
                                                                        $model,
                                                                        'archivo_dni_dorso',
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
                                                                            'language' =>
                                                                            'es',
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
                                                                                'previewFileType' =>
                                                                                'image',
                                                                                'resizeImages' => true,
                                                                                'mainClass' =>
                                                                                'input-group-sm',
                                                                                'uploadUrl' => Url::to(
                                                                                    [
                                                                                        '/sds_ent_entrega/update',
                                                                                    ]
                                                                                ),
                                                                                'maxFileSize' => 3000,
                                                                                'initialPreview' => [
                                                                                    Html::img(
                                                                                        $model->dni_dorso,
                                                                                        [
                                                                                            'class' =>
                                                                                            'file-preview-image',
                                                                                            'style' =>
                                                                                            'width:100%',
                                                                                        ]
                                                                                    ),
                                                                                ],
                                                                                'overwriteInitial' => true,
                                                                                'autoReplace' => true,
                                                                                'initialCaption' =>
                                                                                $model->dni_dorso,
                                                                                'fileActionSettings' => [
                                                                                    'showRemove' => false,
                                                                                    'showUpload' => false,
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
                                                                    ->label(
                                                                        'DNI DORSO'
                                                                    );
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-group" id="accordion_mapa">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#mapa">
                                                Ubicación en el Mapa
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="mapa" class="accordion-body collapse" aria-expanded="false">
                                        <div class="panel-body" id="mapa_content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php if (
                                                        $model->isNewRecord
                                                    ) {
                                                        $model->latitud = -38.951678;
                                                    } ?>
                                                    <?= $form
                                                        ->field(
                                                            $model,
                                                            'latitud'
                                                        )
                                                        ->textInput([
                                                            'id' =>
                                                            'txtLatitud',
                                                            'maxlength' => true,
                                                            'tabIndex' => '0',
                                                        ]) ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php if (
                                                        $model->isNewRecord
                                                    ) {
                                                        $model->longitud = -68.059188;
                                                    } ?>
                                                    <?= $form
                                                        ->field(
                                                            $model,
                                                            'longitud'
                                                        )
                                                        ->textInput([
                                                            'id' =>
                                                            'txtLongitud',
                                                            'maxlength' => true,
                                                            'tabIndex' => '0',
                                                        ]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- <div class="col-md-12">
                                                    <?php /* echo $form
                                                        ->field(
                                                            $model,
                                                            'coordenadas'
                                                        )
                                                        ->widget(
                                                            '\pigolab\locationpicker\CoordinatesPicker',
                                                            [
                                                                'key' =>
                                                                'AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg', // require , Put your google map api key
                                                                'valueTemplate' =>
                                                                '{latitude},{longitude}', // Optional , this is default result format
                                                                'options' => [
                                                                    'style' =>
                                                                    'width: 100%; height: 305px',
                                                                    'tabIndex' =>
                                                                    '0', // map canvas width and height
                                                                ],
                                                                'enableSearchBox' => false, // Optional , default is true
                                                                'searchBoxOptions' => [
                                                                    // searchBox html attributes
                                                                    'style' =>
                                                                    'width: 300px;display:none;', // Optional , default width and height defined in css coordinates-picker.css
                                                                ],
                                                                'searchBoxPosition' => new JsExpression(
                                                                    'google.maps.ControlPosition.TOP_LEFT'
                                                                ), // optional , default is TOP_LEFT
                                                                'mapOptions' => [
                                                                    // google map options
                                                                    // visit https://developers.google.com/maps/documentation/javascript/controls for other options
                                                                    'mapTypeControl' => false, // Enable Map Type Control
                                                                    'mapTypeControlOptions' => [
                                                                        'style' => new JsExpression(
                                                                            'google.maps.MapTypeControlStyle.HORIZONTAL_BAR'
                                                                        ),
                                                                        'position' => new JsExpression(
                                                                            'google.maps.ControlPosition.TOP_LEFT'
                                                                        ),
                                                                    ],
                                                                    'streetViewControl' => false, // Enable Street View Control
                                                                ],
                                                                'clientOptions' => [
                                                                    // jquery-location-picker options
                                                                    'location' => [
                                                                        'latitude' =>
                                                                        $model->latitud,
                                                                        'longitude' =>
                                                                        $model->longitud,
                                                                    ],
                                                                    'radius' => 0,
                                                                    'addressFormat' =>
                                                                    'street_number',
                                                                    'inputBinding' => [
                                                                        'latitudeInput' => new JsExpression(
                                                                            "$('#txtLatitud')"
                                                                        ),
                                                                        'longitudeInput' => new JsExpression(
                                                                            "$('#txtLongitud')"
                                                                        ),
                                                                    ],
                                                                ],
                                                            ]
                                                        )
                                                        ->label(''); */ ?>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="panel-group" id="accordion_adjunto_acta">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#adjunto_acta">
                                                Archivo de Acta
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="adjunto_acta" class="accordion-body collapse" aria-expanded="false">
                                        <div class="panel-body" id="mapa_content">
                                            <?php if ($model->acta == null) {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'archivo_acta',
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
                                                                'image/*,.pdf',
                                                            ],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                //'showPreview' => false,
                                                                'allowedFileExtensions' => [
                                                                    'pdf',
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
                                                                'mainClass' =>
                                                                'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/sds_ent_entrega/update',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 10000,
                                                                /* 'initialPreview'=>[
                                                                      Html::img($model->Foto,['class'=>'file-preview-image']),
                                                                      ], */
                                                                'previewFileType' =>
                                                                'file',
                                                                'initialCaption' => false,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => true,
                                                                    'showUpload' => false,
                                                                ],
                                                                //'minFileCount' => 1,
                                                                // 'validateInitialCount' => true,
                                                            ],
                                                        ]
                                                    )
                                                    ->label('Archivo de Acta');
                                            } else {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'archivo_acta',
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
                                                                'image/*,.pdf',
                                                            ],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => [
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'gif',
                                                                    'png',
                                                                    'bmp',
                                                                    'pdf',
                                                                ],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' =>
                                                                'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/sds_ent_entrega/update',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 10000,
                                                                'previewFileType' =>
                                                                'file',
                                                                'initialPreview' => [
                                                                    Url::to(
                                                                        '@web/' .
                                                                            $model->acta,
                                                                        true
                                                                    ),
                                                                    [
                                                                        'class' =>
                                                                        'file-preview-image',
                                                                        'style' =>
                                                                        'width:100%',
                                                                    ],
                                                                ],
                                                                'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                                                'initialPreviewFileType' => Mds_org_documento::getExtension(
                                                                    $model->acta
                                                                ), // image is the default and can be overridden in config below
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
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
                                                    ->label('Archivo de Acta');
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <?= $form
                                    ->field($model, 'idpersona')
                                    ->hiddenInput()
                                    ->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!Yii::$app->request->isAjax) { ?>
                        <div class="row" style="padding-top: 2%">
                            <div class="col-md-3 col-md-offset-9" style="text-align:right">
                                <div class="form-group">
                                    <a class="btn btn-info" href="index.php?r=sds_ent_entrega&estado=2">Volver </a>
                                    <?= Html::submitButton(
                                        $model->isNewRecord
                                            ? 'Guardar Y Agregar Otra'
                                            : 'Actualizar Datos',
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
            </div>
        </section>
    </div>
</div>
<?php
Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    'id' => 'modal_abm',
    'size' => 'modal-md',
]);

echo "<div id='content_abm'></div>";

Modal::end();

$this->registerJS(
    // register jQuery extension
    "jQuery.extend(jQuery.expr[':'], {
        focusable: function (el, index, selector) {          
            /* return ($(el).is(':input') || $(el).attr('tabindex')>0)
            || ($(el).is('a,button') && $(el).attr('tabindex')>0); */  
            return $(el).attr('tabindex')>0;
        }
    });
    
    /* $( ':focusable' ).css( 'border-color', '#FF9933' );  */
    
    $(document).on('keypress', 'input,select,a,button', function (e) {
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            // Get all focusable elements on the page
            var canfocus = $(':focusable');            
            var index = canfocus.index(this) + 1;
            if (index >= canfocus.length) index = 0;      
            canfocus.eq(index).focus();            
        }
    });"
);
?>

<?php
$this->registerJs(
    "var primeraVez = true;

    $(document).ready(function() {        
        datos_persona(true);
        " .
        ($model->isNewRecord && empty($model->getErrors())
            ? 'cargarEmisores(0);'
            : 'getSaldo();') .
        "
    });
    $('#txtDNI').focusout(function(){
        datos_persona(false);
        if ($('#num_container:visible').length == 0){
            $('#sds_ent_entrega-cantidad').focus();
        }
        else {
            $('#sds_ent_entrega-numero').focus();
        }
    });
    $('#btn_dni').click(function(){
        datos_persona(false);
    });
    $('#cmb_tipo').change(function(){
        cargarEmisores(0);
        habilitarNumero();
    });
    $('#cmb_emisor').change(function(){
        getSaldo();
        setFechaNumero(0);
    });
    $('#fecha_entrega').change(function(){        
        cargarEmisores(0);
    });    
    "
);

$this->registerJS(
    // register jQuery extension
    "jQuery.extend(jQuery.expr[':'], {
        focusable: function (el, index, selector) {          
            /* return ($(el).is(':input') || $(el).attr('tabindex')>0)
            || ($(el).is('a,button') && $(el).attr('tabindex')>0); */  
            return $(el).attr('tabindex')>0;
        }
    });
    
    /* $( ':focusable' ).css( 'border-color', '#FF9933' );  */
    
    $(document).on('keypress', 'input,select,a,button', function (e) {
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            // Get all focusable elements on the page
            var canfocus = $(':focusable');            
            var index = canfocus.index(this) + 1;
            if (index >= canfocus.length) index = 0;      
            canfocus.eq(index).focus();            
        }
    });"
);
?>

<script>
    function cargarEmisores(cambiarFecha = 1) {
        var idtipo = $("#cmb_tipo").val();
        if (idtipo != '') {
            var fecha_hora_entrega = $('#fecha_entrega').val();
            if (fecha_hora_entrega != null) {
                fecha_hora_entrega = formatearFechaInverse($('#fecha_entrega').val());
                fecha_hora_entrega = fecha_hora_entrega + " " + $('#hora').val();
            }
            $.post("index.php?r=sds_ent_entrega/cmb_emisor&idtipo=" + idtipo + "&fecha_entrega=" + fecha_hora_entrega, function(data) {
                console.log(data);
                $("select#cmb_emisor").html(data);
                getSaldo();
                setFechaNumero(cambiarFecha);
            });
        }
    }

    function formatearFechaInverse(fecha) {
        var day = fecha.substring(0, 2);
        var month = fecha.substring(3, 5);
        var year = fecha.substring(6, 10);
        var today = year + "-" + month + "-" + day;
        return today;
    }

    function setFechaNumero(cambiarFecha = 1) {
        var idtipo = $("#cmb_tipo").val();
        var idemisor = $("#cmb_emisor").val();
        if (idtipo != '' && idemisor != '') {
            $.post("index.php?r=sds_ent_entrega/set_fecha_numero&idtipo=" + idtipo + "&idemisor=" + idemisor, function(data) {
                data = jQuery.parseJSON(data);
                if (cambiarFecha == 1) {
                    $("#fecha_entrega").val(formatearFecha(data['fecha_hora']));
                }
                if (data['numero'] != null) {
                    $("#sds_ent_entrega-numero").val(data['numero']);
                }
            });
        }
    }

    function habilitarNumero() {
        var idtipo = $("#cmb_tipo").val();
        console.log(idtipo);
        if (idtipo != '') {
            $.post("index.php?r=sds_ent_entrega/habilitar_numero&idtipo=" + idtipo, function(data) {
                if (data == 1) {
                    $("#num_container").show();
                    $("#sds_ent_entrega-cantidad").val(1);
                    $("#sds_ent_entrega-cantidad").prop("readonly", true);
                    $('#sds_ent_entrega-numero').focus();
                } else {
                    $("#num_container").hide();
                    $("#sds_ent_entrega-cantidad").prop("readonly", false);
                    $('#sds_ent_entrega-cantidad').focus();
                }
            });
        }
    }

    function getSaldo() {
        $.post("index.php?r=sds_ent_entrega/get_saldo&idtipo=" + $("#cmb_tipo").val() + "&identrega=" + $("#cmb_emisor").val(), function(data) {
            var identregaemisor = Number($("#cmb_emisor").val());
            var identregaeditar = Number(<?= $model->emisor != null
                                                ? $model->emisor
                                                : 0 ?>);
            if (identregaeditar == identregaemisor) {
                data = Number($("#sds_ent_entrega-cantidad").val()) + Number(data);
            }
            $("#sds_ent_entrega-saldo").val(data);
        });
    }

    var dni = <?php echo isset($model->dni) ? $model->dni : 0; ?>;

    function datos_persona(primera_vez = false) {
        var dni_campo = $('#txtDNI').val();
        $("#loading").show();
        if (dni != dni_campo || primera_vez) {
            if (dni_campo != '') {
                $('#txt_mensaje').html("Buscando datos de Persona...");
                dni = dni_campo;
                $.post("index.php?r=sds_com_persona/validar_dni&dni=" + dni, function(data) {
                    data = $.parseJSON(data);
                    /* if (!primera_vez) {
                        var identrega = <?php
                                        /* echo isset($model->identrega) ? $model->identrega : 0 */
                                        ?>;
                        $('#txt_mensaje').html("Buscando Fotos Frente y Dorso...");
                        $.post("index.php?r=sds_ent_entrega/dni_frente_dorso&dni=" + dni +
                            "&identrega=" + identrega,
                            function(data) {

                            });
                    } */
                    if (data.length === 0) {
                        datos_renaper(dni);
                        //$("#loading").hide();
                    } else {
                        $("#sds_ent_entrega-idpersona").val(data[0]['idpersona']);
                        $("#sds_ent_entrega-nombre").val(data[0]['nombre']);
                        $("#sds_ent_entrega-apellido").val(data[0]['apellido']);
                        $("#fecha_nacimiento").val(formatearFecha(data[0]['fecha_nacimiento']));
                        $("#sds_ent_entrega-nacionalidad").val(data[0]['nacionalidad']);
                        $("#sds_ent_entrega-sexo").val(data[0]['genero']);
                        /* $("#renaper_foto").attr("src", ''); */
                        $('#txt_mensaje').html("");
                        habilitar_controles();
                        habilitarNumero();
                        $("#loading").hide();
                    }
                });
            } else {
                $("#loading").hide();
            }
        } else {
            $("#loading").hide();
        }
    }

    function datos_renaper(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            console.log(data);
            if (data.status == "error") {
                $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos();
                $("#loading").hide();
            } else {
                var nombre = "";
                var apellido = "";
                var domicilio = "";
                var localidad = "";
                var foto = "";
                $.each(data, function(ind, elem) {
                    if (ind == 'records') {
                        nombre = corregir_palabra(elem[0].result.nombres);
                        apellido = corregir_palabra(elem[0].result.apellido);
                        domicilio = corregir_palabra(elem[0].result.calle + " " + elem[0].result.numero);
                        localidad = corregir_palabra(elem[0].result.ciudad);
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                        //foto = elem[0].result.foto;
                    }
                });
                $("#sds_ent_entrega-idpersona").val(0);
                $("#sds_ent_entrega-nombre").val(nombre);
                $("#sds_ent_entrega-apellido").val(apellido);
                $("#fecha_nacimiento").val(fecha_nacimiento);
                $("#sds_ent_entrega-nacionalidad").val(70);
                nombre = nombre.split(' ', 1)[0];
                sexo = nombre.substr(nombre.length - 1, 1) == 'a' || nombre.includes('Amancay');
                $("#sds_ent_entrega-sexo").val(sexo ? 81 : 82);
                /* $("#renaper_domicilio").html("<b>Domicilio: </b>" + domicilio);
                $("#renaper_localidad").html("<b>Localidad: </b>" + localidad.replace("ï¿½", "É").replace(/_/g, " ")); */
                /* $("#renaper_foto").attr("src", foto); */
                $('#txt_mensaje').html("");
                habilitar_controles();
                habilitarNumero();
                $("#loading").hide();
            }
        });
    }

    function corregir_palabra(palabra) {
        console.log(palabra);
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#sds_ent_entrega-nombre").val('');
        $("#sds_ent_entrega-apellido").val('');
        $("#fecha_nacimiento").val('');
        $("#sds_ent_entrega-nacionalidad").val('');
        $("#sds_ent_entrega-sexo").val('');
        $("#sds_ent_entrega-telefono").val("");
        $("#sds_ent_entrega-domicilio").val("");
        $("#sds_ent_entrega-localidad").val("");
        /* $("#renaper_foto").attr("src", ''); */
        $("#sds_ent_entrega-idpersona").val('0');
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }

    function habilitar_controles() {
        $("#fecha_entrega").prop("disabled", false);
        $("#hora").prop("disabled", false);
        $("#cmb_tipo").prop("disabled", false);
        $("#sds_ent_entrega-numero").prop("disabled", false);
        $("#cmb_emisor").prop("disabled", false);
        $("#sds_ent_entrega-cantidad").prop("disabled", false);
        $("#sds_ent_entrega-observaciones").prop("disabled", false);
        $("#sds_ent_entrega-nombre").prop("disabled", false);
        $("#sds_ent_entrega-apellido").prop("disabled", false);
        $("#fecha_nacimiento").prop("disabled", false);
        $("#sds_ent_entrega-nacionalidad").prop("disabled", false);
        $("#sds_ent_entrega-sexo").prop("disabled", false);
    }
</script>