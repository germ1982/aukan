<?php

use app\models\Sds_800_atencion_familia;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_interior */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Llamada 0800 - Interior';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
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
<div class="sds-800-atencion-interior-form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <h5><b>Fecha de Atención: </b>
                                <?php echo date_format(
                                    date_create($model->fecha_intervencion),
                                    'd/m/Y H:i'
                                ); ?></h5>
                        </div>

                    </div>
                    <?php //Trampita para que anden los accordion del template con yii ;)
                    echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_detalle">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle" href="#detalle">
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
                                                    $model,
                                                    'lugar_intervencion'
                                                )
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::LUGAR_COMISARIA =>
                                                        'Comisaria',
                                                        Sds_800_atencion_familia::LUGAR_ESCUELA =>
                                                        'Escuela',
                                                        Sds_800_atencion_familia::LUGAR_HOSPITAL =>
                                                        'Centro de Salud/Hospital',
                                                        Sds_800_atencion_familia::LUGAR_OTROS =>
                                                        'Otro',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
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
                                                ->field($model, 'defensora')
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
                    <div class="panel-group" id="accordion_persona">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_persona" href="#persona">
                                        Persona Afectada
                                    </a>
                                </h4>
                            </div>
                            <div id="persona" class="accordion-body collapse in">
                                <div class="panel-body" id="persona_content">
                                    <div class="row">
                                        <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <!--ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el seleccionar dni-->
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
                                                    'name' => 'btn_dni',
                                                    'id' => 'btn_dni',
                                                    'data-request-method' =>
                                                    'post',
                                                    'data-toggle' => 'tooltip',
                                                    'class' =>
                                                    'btn btn-primary',
                                                    'title' => Yii::t(
                                                        'app',
                                                        'Consultar DNI'
                                                    ),
                                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el botón de buscar dni
                                                    'disabled' => !$model->isNewRecord,
                                                    'onclick' =>
                                                    '$.post("index.php?r=sds_800_atencion_interior/validar_dni&dni='
                                                        . '"+$("#txtDNI").val()+"&idllamada='
                                                        . $model->idllamada
                                                        . '", function(data)
                                                    {                                                                                                              
                                                        var data = $.parseJSON(data);
                                                        $("#idcomponentequemuestramensaje").val(data.mensaje);
                                                        $("#sds_800_atencion_interior-apellido").val(data[1].apellido);
                                                        $("#sds_800_atencion_interior-nombre").val(data[1].nombre);
                                                        $("#sds_800_atencion_interior-idpersona").val(data[1].idpersona);          
                                                    });',
                                                ]
                                            )
                                                . Html::a(
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
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'apellido')
                                                ->textInput() ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'nombre')
                                                ->textInput() ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'telefono')
                                                ->textInput() ?>
                                        </div>

                                        <div class="col-md-4">
                                            <?= $form->field($model, 'provincia')->widget(
                                                Select2::class,
                                                [
                                                    'data' => $listProvincias,
                                                    'options' => [
                                                        'placeholder' => 'Seleccionar Provincia ...',
                                                        'id' => 'cmb_provincia',
                                                        'onchange' =>   'cargarLocalidades();'
                                                    ],

                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ]
                                            )->label('Provincia');
                                            ?>
                                        </div>

                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'localidad')
                                                ->widget(Select2::class, [
                                                    'data' => $listLocalidad,
                                                    'options' => [
                                                        'placeholder' => 'Seleccionar Localidad ...',
                                                        'id' => 'cmb_localidad'
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                    ],
                                                ]) ?>
                                            <input type="hidden" id="idLocalidadSelected" name="idLocalidadSelected">

                                        </div>
                                    </div>
                                    <?= $form
                                        ->field($model, 'idpersona')
                                        ->hiddenInput()
                                        ->label(false) ?>
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
                                                ->field($model, 'dni1')
                                                ->textInput(['id' => 'txtDNI1'])
                                                ->label('DNI referente') ?>
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
                                                    'data-toggle' => 'tooltip',
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
                                                        'name' => 'btn_pui1',
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
                                                ->field($model, 'nombre1')
                                                ->textInput([
                                                    'disabled' => 'true',
                                                ])
                                                ->label('Nombre Referente') ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'apellido1')
                                                ->textInput([
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php
                                            if (
                                                $model->fecha_nacimiento1 !=
                                                null
                                            ) {
                                                $model->fecha_nacimiento1 = date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        str_replace(
                                                            '/',
                                                            '-',
                                                            $model->fecha_nacimiento1
                                                        )
                                                    )
                                                );
                                            }
                                            echo $form
                                                ->field(
                                                    $model,
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
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'nacionalidad1')
                                                ->dropdownList(
                                                    $listNacionalidad,
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar Nacionalidad ...',
                                                        'disabled' => 'true',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'sexo1')
                                                ->dropdownList(
                                                    $listGenero,
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar Género ...',
                                                        'disabled' => 'true',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">

                                            <?= $form
                                                ->field($model, 'parentezco')
                                                ->dropdownList(
                                                    $listParentesco,
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                        //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                                        //  "disabled" => true,
                                                        'id' =>
                                                        'config_' .
                                                            Sds_com_configuracion_tipo::TIPO_PARENTEZCO,
                                                    ]
                                                ) ?>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'provincia1')->widget(
                                                Select2::class,
                                                [
                                                    'data' => $listProvincias,
                                                    'options' => [
                                                        'placeholder' => 'Seleccionar Provincia ...',
                                                        'id' => 'cmb_provincia1',
                                                        'onchange' =>   'cargarLocalidadesAfectivo();'
                                                    ],

                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ]
                                            )->label('Provincia');
                                            ?>
                                        </div>

                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'localidad1')
                                                ->widget(Select2::class, [
                                                    'data' => $listLocalidad1,
                                                    'options' => [
                                                        'placeholder' => 'Seleccionar Localidad ...',
                                                        'id' => 'cmb_localidad1'
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                    ],
                                                ]) ?>
                                            <input type="hidden" id="idLocalidadAfectivoSelected" name="idLocalidadAfectivoSelected">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'domicilio1')
                                                ->textInput([
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'telefono1')
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
                            <div id="situacion" class="accordion-body collapse in">
                                <div class="panel-body" id="situacion_content">
                                    <div class="row">
                                        <div class="col-md-12" id="interior_plan_accion_texto_container">
                                            <?= $form->field($model, 'plan_accion')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'interior_plan_accion_texto',
                                                ],
                                            ])->label("CONSIDERACIONES/PLAN DE ACCIÓN") ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php if ($model->archivo_adjunto == null) {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'temp_archivo_adjunto',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(FileInput::class, [
                                                        'options' => [
                                                            'accept' => 'image/*,.pdf',
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
                                                            'uploadUrl' => Url::to([
                                                                '/mds_com_intervencion/update',
                                                            ]),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialCaption' =>
                                                            $model->archivo_adjunto,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ],
                                                        ],
                                                    ]);
                                            } else {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'temp_archivo_adjunto',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(FileInput::class, [
                                                        'options' => [
                                                            'accept' => 'image/*,.pdf',
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
                                                            'uploadUrl' => Url::to([
                                                                '/sds_800_atencion/update',
                                                            ]),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialPreview' => [
                                                                Html::img(
                                                                    $model->archivo_adjunto,
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
                                                            $model->archivo_adjunto,
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
                                                    ]);
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?= $form
                            ->field($model, 'idusuario')
                            ->hiddenInput()
                            ->label(false) ?>

                        <?= $form
                            ->field($model, 'idllamada')
                            ->hiddenInput()
                            ->label(false) ?>

                        <?= $form
                            ->field($model, 'idpersona_referente')
                            ->hiddenInput()
                            ->label(false) ?>
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if (
                                    !Yii::$app->request->isAjax
                                ) { ?>
                                    <div class="form-group">
                                        <?= Html::submitButton(
                                            $model->isNewRecord
                                                ? 'Crear'
                                                : 'Modificar',
                                            [
                                                'class' => $model->isNewRecord
                                                    ? 'btn btn-success'
                                                    : 'btn btn-primary',
                                                'id' => 'btnGuardar'
                                            ]
                                        ) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php $this->registerJs(
    "$(document).ready(function() {        
datos_persona1(true);
});


$('#txtDNI1').change(function(){        
    datos_persona1(false);
});


$('#btn_dni1').show();
$('#btn_dni1').click(function(){        
    datos_persona1(false);
 });

$('#btn_pui').click(function(){        
var dni_campo = $('#txtDNI').val();        
window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
});

$('#btn_pui1').click(function(){        
    var dni_campo1 = $('#txtDNI1').val();        
    window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo1, '_blank');
    });

$('#btnGuardar').click(function(e){
    const plan_accion_texto =  $('#interior_plan_accion_texto').val();
    const parser = new DOMParser();
    const { textContent } = parser.parseFromString(plan_accion_texto, 'text/html').documentElement;
    plan_accionTextoSinHTML = textContent.trim();

    if (!plan_accion_texto || plan_accion_texto.length < 1 || !plan_accionTextoSinHTML){
        alert('\"CONSIDERACIONES/PLAN DE ACCIÓN\" no puede estar vacío.');
        e.preventDefault();
    }
});
"
); ?>
<script>
    function cargarLocalidades() {
        if ($("#cmb_provincia").val()) {
            $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {
                $("select#cmb_localidad").html(data);
                $("#cmb_localidad").val($("#idLocalidadSelected").val()).trigger("change");
            });
        } else {
            $("#cmb_localidad").val(null).trigger("change");
        }
    }

    function cargarLocalidadesAfectivo() {
        if ($("#cmb_provincia1").val()) {
            $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia1").val(), function(data) {
                $("select#cmb_localidad1").html(data);
                $("#cmb_localidad1").val($("#idLocalidadAfectivoSelected").val()).trigger("change");
            });
        } else {
            $("#cmb_localidad1").val(null).trigger("change");
        }
    }

    var dni1 = <?php echo isset($model->dni1) ? $model->dni1 : 0; ?>;

    function datos_persona1(primera_vez = false) {
        var dni_campo = $('#txtDNI1').val();
        if (dni1 != dni_campo || primera_vez) {
            if (dni_campo != '') {
                $('#txt_mensaje1').html("Buscando datos de Persona...");
                dni1 = dni_campo;
                $.post("index.php?r=sds_800_atencion/validar_dni&dni=" + dni1, function(data) {
                    data = $.parseJSON(data);
                    if (data.length === 0) {
                        datos_renaper1(dni1);
                    } else {
                        $("#sds_800_atencion_interior-idpersona_referente").val(data[0]['idpersona']);
                        $("#sds_800_atencion_interior-nombre1").val(data[0]['nombre']);
                        $("#sds_800_atencion_interior-apellido1").val(data[0]['apellido']);
                        $("#fecha_nacimiento1").val(formatearFecha(data[0]['fecha_nacimiento']));
                        $("#sds_800_atencion_interior-nacionalidad1").val(data[0]['nacionalidad']);
                        $("#sds_800_atencion_interior-sexo1").val(data[0]['genero']);
                        if (data.length > 1) {
                            $("#sds_800_atencion_interior-localidad1").val(data[1]['idlocalidad']);
                        } else {
                            $("#sds_800_atencion_interior-localidad1").val("");
                        }
                        $('#txt_mensaje').html("");
                        habilitar_controles1();
                    }
                });
            }
        }
    }

    function datos_renaper1(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            if (data.status == "error") {
                $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos1();
            } else {
                var nombre1 = "";
                var apellido1 = "";
                var localidad1 = "";
                var foto1 = "";
                var fecha_nacimiento1 = null;
                $.each(data, function(ind, elem) {
                    console.log(ind);
                    if (ind == 'records') {
                        console.log(elem[0]);
                        nombre1 = elem[0].result.nombres;
                        apellido1 = elem[0].result.apellido;
                        localidad1 = elem[0].result.ciudad;
                        foto1 = elem[0].result.foto;
                        fecha_nacimiento1 = elem[0].result.fecha_nacimiento;
                    }
                });
                if (fecha_nacimiento1 != null) {
                    $("#sds_800_atencion_interior-nombre1").val(corregir_palabra(nombre1));
                    $("#sds_800_atencion_interior-apellido1").val(corregir_palabra(apellido1));
                    $("#fecha_nacimiento1").val(fecha_nacimiento1);
                    $("#sds_800_atencion_interior-nacionalidad1").val('');
                    $("#sds_800_atencion_interior-sexo1").val('');
                    $("#sds_800_atencion_interior-localidad1").val('');
                    //$("#renaper_foto1").attr("src", foto1);
                    $('#txt_mensaje1').html("");
                    habilitar_controles1();
                }
            }
        });
    }

    function limpiarDatos1() {
        habilitar_controles1();
        $("#sds_800_atencion_interior-nombre1").val('');
        $("#sds_800_atencion_interior-apellido1").val('');
        $("#fecha_nacimiento1").val('');
        $("#sds_800_atencion_interior-nacionalidad1").val('');
        $("#sds_800_atencion_interior-sexo1").val('');
        $("#sds_800_atencion_interior-telefono1").val("");
        $("#sds_800_atencion_interior-domicilio1").val("");
        $("#sds_800_atencion_interior-localidad1").val("");
        $("#sds_800_atencion_interior-idpersona_referente").val('0');
    }

    function habilitar_controles1() {
        $("#sds_800_atencion_interior-nombre1").prop("disabled", false);
        $("#sds_800_atencion_interior-apellido1").prop("disabled", false);
        $("fecha_nacimiento1").prop("disabled", false);
        $("#sds_800_atencion_interior-nacionalidad1").prop("disabled", false);
        $("#sds_800_atencion_interior-sexo1").prop("disabled", false);
        $("#sds_800_atencion_interior-localidad1").prop("disabled", false);
        $("#sds_800_atencion_interior-telefono1").prop("disabled", false);
        $("#sds_800_atencion_interior-parentezco").prop("disabled", false);
        $("#sds_800_atencion_interior-domicilio1").prop("disabled", false);
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }
</script>