<?php

use app\models\Sds_800_atencion_familia;
use app\models\Sds_800_llamada;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use pigolab\locationpicker\CoordinatesPicker;
use yii\bootstrap\Collapse;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion */
?>
<style>
    .alert-detalle {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
        max-height: 300px;
        overflow-y: auto;
    }
</style>
<?php if ($model_atencion != null) : ?>
    <div class="sds-800-atencion-view">
        <header class="page-header">
            <h2>Llamada 0800 Interior.</h2>

            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Llamada 0800 Interior</span></li>
                </ol>

                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12">
                <section class="panel">
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin([
                            'disabled' => true,
                        ]); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h5><b>Fecha de Atención: </b>
                                    <?php echo date_format(
                                        date_create(
                                            $model_atencion->fecha_intervencion
                                        ),
                                        'd/m/Y H:i'
                                    ); ?></h5>
                            </div>

                        </div>
                        <br>
                        <?php echo Collapse::widget([]); ?>
                        <div class="panel-group" id="accordion_atencion">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_atencion" href="#atencion">
                                            Lugar de Intervención
                                        </a>
                                    </h4>
                                </div>
                                <div id="detalle" class="accordion-body collapse in">
                                    <div class="panel-body" id="detalle_content">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'lugar_intervencion'
                                                    )
                                                    ->dropDownList([
                                                        null => 'Selecione una opción...',
                                                        Sds_800_atencion_familia::LUGAR_COMISARIA =>
                                                        'Comisaria',
                                                        Sds_800_atencion_familia::LUGAR_ESCUELA =>
                                                        'Escuela',
                                                        Sds_800_atencion_familia::LUGAR_HOSPITAL =>
                                                        'Centro de Salud/Hospital',
                                                        Sds_800_atencion_familia::LUGAR_OTROS =>
                                                        'Otro',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'lugar_especificacion'
                                                    )
                                                    ->textInput([
                                                        'maxlength' => true,
                                                    ])
                                                    ->label(
                                                        'Especificar según corresponda'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'defensora'
                                                    )
                                                    ->textInput([
                                                        'maxlength' => true,
                                                    ])
                                                    ->label(
                                                        'Defensora Interviniente'
                                                    ) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion_detalle">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle" href="#detalle">
                                            Persona Afectada
                                        </a>
                                    </h4>
                                </div>
                                <div id="detalle" class="accordion-body collapse in">
                                    <div class="panel-body" id="detalle_content">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row"></div>
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'dni'
                                                    )
                                                    ->textInput([
                                                        'id' => 'txtDNI',
                                                    ]) ?>
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
                                                        'data-toggle' =>
                                                        'tooltip',
                                                        'class' =>
                                                        'btn btn-primary',
                                                        'title' => Yii::t(
                                                            'app',
                                                            'Consultar DNI persona afectada'
                                                        ),
                                                    ]
                                                ) .
                                                    Html::a(
                                                        '<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">',
                                                        null,
                                                        [
                                                            'name' => 'btn_pui',
                                                            'id' => 'btn_pui',
                                                            'data-request-method' =>
                                                            'post',
                                                            'data-toggle' =>
                                                            'tooltip',
                                                            'style' =>
                                                            'padding:0px;padding-left:2px;',
                                                            'class' => 'btn',
                                                            'title' => Yii::t(
                                                                'app',
                                                                'Consulta a Portal Unificado'
                                                            ),
                                                        ]
                                                    ); ?>
                                            </div>
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'nombre'
                                                    )
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'apellido'
                                                    )
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'telefono'
                                                    )
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Localidad</label>
                                                <input type="text" class="form-control" value="<?php echo ($localidadDescripcion) ? $localidadDescripcion : '' ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-group" id="accordion_afectivo">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_afectivo" href="#afectivo">
                                            Referente Afectivo
                                        </a>
                                    </h4>
                                </div>
                                <div id="afectivo" class="accordion-body collapse in">
                                    <div class="panel-body" id="afectivo_content">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row"></div>
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'dni1'
                                                    )
                                                    ->textInput([
                                                        'id' => 'txtDNI1',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-3" style="padding-top:25px;">
                                                <?php echo Html::a(
                                                    '<i class="glyphicon glyphicon-search"></i>',
                                                    null,
                                                    [
                                                        'name' => 'btn_dni1',
                                                        'id' => 'btn_dni1',
                                                        'data-request-method' =>
                                                        'post',
                                                        'data-toggle' =>
                                                        'tooltip',
                                                        'class' =>
                                                        'btn btn-primary',
                                                        'title' => Yii::t(
                                                            'app',
                                                            'Consultar DNI referente'
                                                        ),
                                                    ]
                                                ) .
                                                    Html::a(
                                                        '<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">',
                                                        null,
                                                        [
                                                            'name' =>
                                                            'btn_pui1',
                                                            'id' => 'btn_pui1',
                                                            'data-request-method' =>
                                                            'post',
                                                            'data-toggle' =>
                                                            'tooltip',
                                                            'style' =>
                                                            'padding:0px;padding-left:2px;',
                                                            'class' => 'btn',
                                                            'title' => Yii::t(
                                                                'app',
                                                                'Consulta a Portal Unificado'
                                                            ),
                                                        ]
                                                    ); ?>
                                            </div>
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje1">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'nombre1'
                                                    )
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'apellido1'
                                                    )
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?php
                                                if (
                                                    $model_atencion->fecha_nacimiento1 !=
                                                    null
                                                ) {
                                                    $model_atencion->fecha_nacimiento1 = date(
                                                        'd/m/Y',
                                                        strtotime(
                                                            str_replace(
                                                                '/',
                                                                '-',
                                                                $model_atencion->fecha_nacimiento1
                                                            )
                                                        )
                                                    );
                                                }
                                                echo $form
                                                    ->field(
                                                        $model_atencion,
                                                        'fecha_nacimiento1'
                                                    )
                                                    ->widget(
                                                        DatePicker::class,
                                                        [
                                                            'name' =>
                                                            'check_issue_date',
                                                            'language' => 'es',
                                                            'readonly' => false,
                                                            'layout' =>
                                                            '{picker}{input}{remove}',
                                                            'options' => [
                                                                'id' =>
                                                                'fecha_nacimiento1',
                                                                // 'disabled' => true
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label class="form-label">Nacionalidad</label>
                                                <input type="text" class="form-control" value="<?php echo ($nacionalidad1Descripcion) ? $nacionalidad1Descripcion->descripcion : '' ?>" readonly>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="form-label">Sexo</label>
                                                <input type="text" class="form-control" value="<?php echo ($genero1Descripcion) ? $genero1Descripcion->descripcion : '' ?>" readonly>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="form-label">Parentesco</label>
                                                <input type="text" class="form-control" value="<?php echo ($parentescoDescripcion) ? $parentescoDescripcion->descripcion : '' ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label class="form-label">Localidad</label>
                                                <input type="text" class="form-control" value="<?php echo ($localidad1Descripcion) ? $localidad1Descripcion : '' ?>" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'telefono1'
                                                    )
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'domicilio1'
                                                    )
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion_situacion">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_situacion" href="#situacion">
                                            Detalle Situación
                                        </a>
                                    </h4>
                                </div>
                                <div id="afectivo" class="accordion-body collapse in">
                                    <div class="panel-body" id="situacion_content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">CONSIDERACIONES/PLAN DE ACCIÓN</label>
                                                <div class="alert alert-detalle" role="alert">
                                                    <p><?php echo $model_atencion->plan_accion;  ?></p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class='col-md-12'>
                                        <?php if (
                                            $model_atencion->archivo_adjunto ==
                                            null
                                        ) {
                                            echo $form
                                                ->field(
                                                    $model_atencion,
                                                    'temp_archivo_adjunto',
                                                    [
                                                        'enableClientValidation' => true,
                                                        'enableAjaxValidation' => false,
                                                    ]
                                                )
                                                ->widget(
                                                    FileInput::class,
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
                                                                    '/mds_com_intervencion/update',
                                                                ]
                                                            ),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' =>
                                                            'file',
                                                            'initialCaption' =>
                                                            $model_atencion->archivo_adjunto,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ],
                                                        ],
                                                    ]
                                                );
                                        } else {
                                            echo $form
                                                ->field(
                                                    $model_atencion,
                                                    'temp_archivo_adjunto',
                                                    [
                                                        'enableClientValidation' => true,
                                                        'enableAjaxValidation' => false,
                                                    ]
                                                )
                                                ->widget(
                                                    FileInput::class,
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
                                                                    '/sds_800_atencion/update',
                                                                ]
                                                            ),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' =>
                                                            'file',
                                                            'initialPreview' => [
                                                                Html::img(
                                                                    $model_atencion->archivo_adjunto,
                                                                    [
                                                                        'class' =>
                                                                        'file-preview-image',
                                                                        'style' =>
                                                                        'width:100%; text-align: center',
                                                                    ]
                                                                ),
                                                            ],
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'initialCaption' =>
                                                            $model_atencion->archivo_adjunto,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
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
                                                );
                                        } ?>
                                    </div>
                                </div>

                                <?= $form
                                    ->field($model_atencion, 'idusuario')
                                    ->hiddenInput()
                                    ->label(false) ?>
                                <?= $form
                                    ->field($model_atencion, 'idllamada')
                                    ->hiddenInput()
                                    ->label(false) ?>
                                <?= $form
                                    ->field($model_atencion, 'idpersona')
                                    ->hiddenInput()
                                    ->label(false) ?>
                                <?= $form
                                    ->field(
                                        $model_atencion,
                                        'idpersona_referente'
                                    )
                                    ->hiddenInput()
                                    ->label(false) ?>
                                <?php ActiveForm::end(); ?>
                            </div>
                </section>
            </div>
        </div>

    </div>
<?php endif; ?>

<div class="sds-800-llamada-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel-group" id="accordion_llamante">
        <div class="panel panel-accordion">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_llamante" href="#llamante">
                        Datos del Llamante
                    </a>
                </h4>
            </div>
            <div id="llamante" class="accordion-body collapse in">
                <div class="panel-body" id="llamante_content">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'dni')->textInput([
                                'id' => 'txtDNI',
                                'disabled' => true
                            ]) ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Solicitante de Situación</label>
                            <input type="text" class="form-control" value="<?php echo ($model->solicitante == 1) ? 'Si' : 'No'; ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form
                                ->field($model, 'nombre')
                                ->textInput(['disabled' => 'true']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form
                                ->field($model, 'apellido')
                                ->textInput(['disabled' => 'true']) ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            if ($model->fecha_nacimiento != null) {
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
                                ->field($model, 'fecha_nacimiento')
                                ->widget(DatePicker::class, [
                                    'name' => 'check_issue_date',
                                    'language' => 'es',
                                    'readonly' => false,
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => [
                                        'id' => 'fecha_nacimiento',
                                        'class' => 'form-control input-md',
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
                                ->label('Fecha de Nacimiento');
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-label">Nacionalidad</label>
                            <input type="text" class="form-control" value="<?php echo ($nacionalidadDescripcion) ? $nacionalidadDescripcion->descripcion : '' ?>" readonly>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Sexo</label>
                            <input type="text" class="form-control" value="<?php echo ($generoDescripcion) ? $generoDescripcion->descripcion : '' ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <?= $form
                                ->field($model, 'telefono')
                                ->textInput(['disabled' => 'true']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <?= $form
                                ->field($model, 'domicilio')
                                ->textInput(['disabled' => 'true']) ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Localidad</label>
                            <input type="text" class="form-control" value="<?php echo ($model->localidad) ? $model->localidad : '' ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'institucion')->textInput([
                                'maxlength' => true,
                                'disabled' => 'true',
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'vinculo')->textInput([
                                'maxlength' => true,
                                'disabled' => 'true',
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-group" id="accordion_situacion_detalle">
        <div class="panel panel-accordion">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_situacion_detalle" href="#detalle">
                        Detalle de la Situación
                    </a>
                </h4>
            </div>
            <div id="detalle" class="accordion-body collapse in">
                <div class="panel-body" id="detalle_content">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Descripción</label>
                            <div class="alert alert-detalle" role="alert">
                                <p><?php echo $model->detalle;  ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?= $form
                                ->field($model, 'afectado_dni')
                                ->textInput(['disabled' => 'true']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form
                                ->field($model, 'afectado_nombre')
                                ->textInput([
                                    'maxlength' => true,
                                    'disabled' => 'true',
                                ]) ?>
                        </div>
                        <div class="col-md-5">
                            <?= $form
                                ->field($model, 'afectado_apodo')
                                ->textInput([
                                    'maxlength' => true,
                                    'disabled' => 'true',
                                ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-group" id="accordion_direccion">
        <div class="panel panel-accordion">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_direccion" href="#direccion">
                        Detalle de la Dirección
                    </a>
                </h4>
            </div>
            <div id="direccion" class="accordion-body collapse in">
                <div class="panel-body" id="direccion_content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form
                                        ->field($model, 'latitud')
                                        ->textInput(['disabled' => 'true']) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form
                                        ->field($model, 'longitud')
                                        ->textInput(['disabled' => 'true']) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form
                                        ->field($model, 'direccion')
                                        ->textInput([
                                            'maxlength' => true,
                                            'readonly' => true,
                                        ]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php echo CoordinatesPicker::widget([
                                'model' => $model,
                                'attribute' => 'coordenadas',
                                'key' =>
                                'AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg', // require , Put your google map api key
                                'valueTemplate' => '{latitude},{longitude}', // Optional , this is default result format
                                'options' => [
                                    'style' => 'width: 100%; height: 405px',
                                    // map canvas width and height
                                ],
                                'enableSearchBox' => false,
                                // Optional , default is true
                                'searchBoxOptions' => [
                                    // searchBox html attributes
                                    'style' => 'width: 300px;display:none;', // Optional , default width and height defined in css coordinates-picker.css
                                ],
                                'searchBoxPosition' => new JsExpression(
                                    'google.maps.ControlPosition.TOP_LEFT'
                                ), // optional , default is TOP_LEFT
                                'mapOptions' => [
                                    // google map options
                                    // visit https://developers.google.com/maps/documentation/javascript/controls for other options
                                    'mapTypeControl' => false,
                                    // Enable Map Type Control
                                    'mapTypeControlOptions' => [
                                        'style' => new JsExpression(
                                            'google.maps.MapTypeControlStyle.HORIZONTAL_BAR'
                                        ),
                                        'position' => new JsExpression(
                                            'google.maps.ControlPosition.TOP_LEFT'
                                        ),
                                    ],
                                    'streetViewControl' => true,
                                    // Enable Street View Control
                                ],
                                'clientOptions' => [
                                    // jquery-location-picker options
                                    'location' => [
                                        'latitude' => $model->latitud,
                                        'longitude' => $model->longitud,
                                    ],
                                    'radius' => 0,
                                    'markerDraggable' => 0,
                                    'addressFormat' => 'street_number',
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-group" id="accordion_detalle_derivacion" style="display:<?= $model->estado ==
                                                                                    Sds_800_llamada::ESTADO_DERIVADA
                                                                                    ? 'block'
                                                                                    : 'none' ?>">
        <div class="panel panel-accordion">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle_derivada" href="#detalle_derivacion">
                        Detalle de Derivación
                    </a>
                </h4>
            </div>
            <div id="detalle_derivacion" class="accordion-body collapse in">
                <div class="panel-body" id="detalle_derivacion_content">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" value="<?php echo ($situacionTipoDescripcion) ? $situacionTipoDescripcion->descripcion : '' ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <?= $form
                                ->field($model, 'idderivacion')
                                ->dropdownList(
                                    $selectDerivacion,
                                    [
                                        'placeholder' =>
                                        'Seleccionar derivación ...',
                                        'id' => 'idderivacion',
                                        'disabled' => true,
                                    ]
                                )
                                ->label('Derivación') ?>
                        </div>
                        <div class="col-md-5" id="derivacion_data" style="padding-top:20px;">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <?= $form
                                ->field($model, 'derivacion_referente')
                                ->textInput(['disabled' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-detalle" role="alert">
                                <p><?php echo $model->derivacion_detalle;  ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-group" id="accordion_detalle_cierre" style="display:<?= $model->estado ==
                                                                                Sds_800_llamada::ESTADO_CERRADA || $model->estado == Sds_800_llamada::ESTADO_DESPEJADA
                                                                                ? 'block'
                                                                                : 'none' ?>">
        <div class="panel panel-accordion">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle_cierre" href="#detalle_cierre">
                        Detalle del Cierre - Despeje
                    </a>
                </h4>
            </div>
            <div id="detalle_cierre" class="accordion-body collapse in">
                <div class="panel-body" id="detalle_cierre_content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-detalle" role="alert">
                                <p><?php echo $model->cierre_detalle;  ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="text-align: right;">
        <?= $form->field($model, 'estado')->checkbox(['disabled' => 'true']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
<?php $this->registerJs("$(document).ready(function() {                
        datos_derivacion();
    });
    "); ?>
<script>
    function datos_derivacion() {
        var idderivacion = $("#idderivacion option:selected").val();
        $.post("index.php?r=sds_800_llamada/get_datos_derivacion&idderivacion=" + idderivacion, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                return "";
            } else {
                $("#derivacion_data").html("<b>Teléfonos:</b> " + data['telefonos'] + "<br><b>Dirección:</b> " + data['direccion']);
            }
        });
    }
</script>