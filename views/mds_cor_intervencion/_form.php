<?php

use app\models\Mds_org_contacto;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_cor_intervencion */
/* @var $form yii\widgets\ActiveForm */

function botonAltaConfiguracion($model)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => Sds_com_configuracion_tipo::TIPO_COR_INTERVENCION_TIPO]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . Sds_com_configuracion_tipo::TIPO_COR_INTERVENCION_TIPO, 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        "disabled" => !$model->isNewRecord,
        'onclick' => '
            $("#abm_configuracion").show();
            $("#abm_configuracion_content").load($(this).attr("value"));
            $("#abm_configuracion_title").html("Agregar Tipo Intervención");
            $("#btnGuardar").hide();$("#btnCerrar").hide();
            $("#interv_form").hide();'
    ]);
}
function botonAltaConfiguracionLey($model)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => Sds_com_configuracion_tipo::TIPO_COR_INTERVENCION_LEY]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . Sds_com_configuracion_tipo::TIPO_COR_INTERVENCION_LEY, 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        "disabled" => !$model->isNewRecord,
        'onclick' => '
            $("#abm_configuracion").show();
            $("#abm_configuracion_content").load($(this).attr("value"));
            $("#abm_configuracion_title").html("Agregar Ley");
            $("#btnGuardar").hide();$("#btnCerrar").hide();
            $("#interv_form").hide();'
    ]);
}
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }

    .select2-search {
        z-index: 1;
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
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
                <div class="mds-cor-intervencion-form" id="interv_form">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="panel-group">
                        <div class="accordion-body collapse in">
                            <label style="display: <?= $model->idllamada ? "block" : "none;" ?>" </label>
                                <div class="panel-body" id="persona_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5><b>Nro. de Atención en Guardias Integradas: </b>
                                                <?php echo ($model->idllamada) ?></h5>
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
                                        Datos de la Persona
                                    </a>
                                </h4>
                            </div>
                            <div id="persona" class="accordion-body collapse in">
                                <div class="panel-body" id="persona_content">
                                    <?= $form->field($model, 'idpersona')->hiddenInput()->label(false) ?>
                                    <div class="row">
                                        <div class="col-md-3 required">
                                            <?= $form->field($model, 'dni_beneficiario')->textInput(["id" => "txtDNI", "disabled" => !$model->isNewRecord]); ?>
                                        </div>
                                        <div class="col-md-2" style="padding-top:25px;">
                                            <?php
                                            echo Html::a('<i class="glyphicon glyphicon-search"></i>', null, [
                                                'name' => 'btn_dni',
                                                'id' => 'btn_dni',
                                                'data-request-method' => 'post',
                                                'data-toggle' => 'tooltip',
                                                'class' => 'btn btn-primary',
                                                'title' => Yii::t('app', 'Consultar DNI'),
                                                //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el botón de buscar dni
                                                "disabled" => !$model->isNewRecord,
                                                'onclick' => '$.post("index.php?r=mds_cor_intervencion/validar_dni&dni=' .
                                                    '"+$("#txtDNI").val(), function(data){                                                                                                              
                                                    var data = $.parseJSON(data);
                                                    $("#idcomponentequemuestramensaje").val(data.mensaje);
                                                    $("#mds_cor_intervencion-apellido").val(data[1].apellido);
                                                    $("#mds_cor_intervencion-nombre").val(data[1].nombre);
                                                    $("#mds_cor_intervencion-idpersona").val(data[1].idpersona);          
                                                    $("#mds_cor_intervencion-fecha_nacimiento").val(data[2].fecha_nacimiento);          
                                                    $("#mds_cor_intervencion-edad").val(data[2].edad);          
                                                    $("#mds_cor_intervencion-genero").val(data[2].genero);    
                                                    $("#mds_cor_intervencion-genero_autopercibido").val(data[2].genero_autopercibido);          
                                                });'
                                            ]) .
                                                Html::a('<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">', null, [
                                                    'name' => 'btn_pui',
                                                    'id' => 'btn_pui',
                                                    'data-request-method' => 'post',
                                                    'data-toggle' => 'tooltip',
                                                    'style' => 'padding:0px;padding-left:2px;',
                                                    'class' => 'btn',
                                                    'title' => Yii::t('app', 'Consulta a Portal Unificado'),
                                                ]);
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'apellido')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'nombre')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'fecha_nacimiento')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-2">
                                            <?= $form->field($model, 'edad')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'genero')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'genero_autopercibido')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_intervencion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                        Datos de la intervencion
                                    </a>
                                </h4>
                            </div>
                            <div id="intervencion" class="accordion-body collapse in">
                                <div class="panel-body" id="intervencion_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= $form->field($model, 'nombre_autopercibido')->textInput() ?>
                                        </div>
                                        <div class="col-md-6 required">
                                            <?= $form->field($model, 'profesional')->widget(Select2::class, [
                                                'data' => ArrayHelper::map(
                                                    Mds_org_contacto::findBySql("select idcontacto,nombre,apellido from mds_org_contacto c 
                                                            join sds_com_persona p on p.idpersona=c.idpersona order by nombre ASC, apellido ASC;")->all(),
                                                    'idcontacto',
                                                    function ($model) {
                                                        return mb_strtoupper($model->nombre) . " " . mb_strtoupper($model->apellido);
                                                    }
                                                ),
                                                'options' => ['prompt' => '-- Seleccione una opción --', "disabled" => !$model->isNewRecord],
                                                'size' => Select2::MEDIUM,
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'fecha_informe')
                                                ->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), le saco la opción de remover
                                                    'layout' => !$model->isNewRecord ? '{picker}{input}' : '{picker}{input}{remove}',
                                                    'options' => [
                                                        'id' => 'fecha_informe',
                                                        'class' => 'form-control input-md',
                                                        //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito la selección del date.
                                                        "disabled" => !$model->isNewRecord
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        'format' => 'dd-mm-yyyy',
                                                        'endDate' => date('d/m/Y'),
                                                        'todayHighlight' => true,
                                                        'autoclose' => true,
                                                    ]
                                                ])->label('Fecha de Intervención'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <div class="input-group required">
                                                <?= $form->field($model, 'tipo')->dropdownList(
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_COR_INTERVENCION_TIPO, true),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),
                                                    [
                                                        'prompt' => '-- Seleccione una opción --',
                                                        //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                                        "disabled" => !$model->isNewRecord,
                                                        'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_COR_INTERVENCION_TIPO
                                                    ]
                                                );
                                                ?>
                                                <span class="input-group-btn">
                                                    <?= botonAltaConfiguracion($model) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <!--ANOTAPRI: Agrego como si fuera un tipo, las leyes -->
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <?= $form->field($model, 'ley')->dropdownList(
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_COR_INTERVENCION_LEY, true),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),
                                                    [
                                                        'prompt' => '-- Seleccione una opción --',
                                                        //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                                        "disabled" => !$model->isNewRecord,
                                                        'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_COR_INTERVENCION_LEY
                                                    ]
                                                );
                                                ?>
                                                <span class="input-group-btn">
                                                    <?= botonAltaConfiguracionLey($model) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'provincia')->widget(Select2::class, [
                                                'data' => $listProvincias,
                                                'options' => [
                                                    'placeholder' => 'Seleccionar Provincia ...',
                                                    'id' => 'cmb_provincia',
                                                    'onchange' => 'cargarLocalidades();',
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ]);
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="hidden" id="idLocalidadSelected" name="idLocalidadSelected">
                                            <?= $form->field($model, 'idlocalidad')->widget(Select2::class, [
                                                'data' => $listLocalidades,
                                                'options' => [
                                                    'placeholder' => 'Seleccionar Localidad ...',
                                                    'id' => 'idlocalidad',
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ]
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'idtiemporesidencianqn')->dropdownList(
                                                ArrayHelper::map(
                                                    Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::COR_INTERVENCION_TIEMPO_RESIDENCIA_NQN, true),
                                                    'idconfiguracion',
                                                    'descripcion'
                                                ),
                                                [
                                                    'prompt' => '-- Seleccione una opción --',
                                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                                    // "disabled" => !$model->isNewRecord,
                                                    'id' => 'config_' . Sds_com_configuracion_tipo::COR_INTERVENCION_TIEMPO_RESIDENCIA_NQN
                                                ]
                                            );
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'iddenuncia')->dropdownList(
                                                ArrayHelper::map(
                                                    Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::COR_INTERVENCION_DENUNCIA, true),
                                                    'idconfiguracion',
                                                    'descripcion'
                                                ),
                                                [
                                                    'prompt' => '-- Seleccione una opción --',
                                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                                    // "disabled" => !$model->isNewRecord,
                                                    'id' => 'config_' . Sds_com_configuracion_tipo::COR_INTERVENCION_DENUNCIA
                                                ]
                                            );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label>Consumos problemáticos</label>
                                            <?= Select2::widget([
                                                'name' => 'consumo',
                                                'id'  => 'consumo',
                                                'value' => $consumos,
                                                'data' => $listConsumos,
                                                'options' => ['multiple' => true],
                                                'showToggleAll' => false,
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label>Salud física</label>
                                            <?= Select2::widget([
                                                'name' => 'problema',
                                                'id'  => 'problema',
                                                'value' => $problemas,
                                                'data' => $listProblemas,
                                                'options' => ['multiple' => true],
                                                'showToggleAll' => false,
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label>Articulación interinstitucional</label>
                                            <?= Select2::widget([
                                                'name' => 'articulacion',
                                                'id'  => 'articulacion',
                                                'value' => $articulaciones,
                                                'data' => $listArticulaciones,
                                                'options' => ['multiple' => true],
                                                'showToggleAll' => false,
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="derivaciones_previas_container">
                                            <?= $form->field($model, 'derivaciones_previas')->widget(\bizley\quill\Quill::class, [
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'derivaciones_previas_texto',
                                                ],
                                            ])->label("Derivaciones previas") ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="plan_accion_container">
                                            <?= $form->field($model, 'plan_accion')->widget(\bizley\quill\Quill::class, [
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'plan_accion_texto',
                                                ],
                                            ])->label("Plan de acción") ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="detalle_container">
                                            <?= $form->field($model, 'detalle')->widget(\bizley\quill\Quill::class, [
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'detalle_texto',
                                                ],
                                            ])->label("Detalle") ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="intervenciones_container">
                                            <?= $form->field($model, 'intervenciones')->widget(\bizley\quill\Quill::class, [
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'intervenciones_texto',
                                                ],
                                            ])->label("Intervencion Realizada") ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="derivaciones_container">
                                            <?= $form->field($model, 'derivaciones')->widget(\bizley\quill\Quill::class, [
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'derivaciones_texto',
                                                ],
                                            ])->label("Derivaciones Futuras") ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_tercero">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_tercero" href="#tercero">
                                        Datos de Tercero Referente
                                    </a>
                                </h4>
                            </div>
                            <div id="tercero" class="accordion-body collapse in">
                                <div class="panel-body" id="tercero_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'referente_dni')->textInput() ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'referente_nombre')->textInput(['maxlength' => true]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'referente_vinculo')->textInput(['maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'referente_telefono')->textInput(['maxlength' => true]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_salud">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_salud" href="#salud">
                                        Adjuntar Archivo
                                    </a>
                                </h4>
                            </div>
                            <div id="salud" class="accordion-body collapse in">
                                <div class="panel-body" id="salud_content">
                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php
                                            if ($model->archivo_adjunto == null) {
                                                echo $form->field($model, 'temp_archivo_salud', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::class, [
                                                        'options' => ['accept' => 'image/*,.pdf'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/mds_com_intervencion/update']),
                                                            'maxFileSize' => 1000000,
                                                            'previewFileType' => 'file',
                                                            'initialCaption' => $model->archivo_adjunto,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                    ]);
                                            } else {
                                                echo $form->field($model, 'temp_archivo_salud', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::class, [
                                                        'options' => ['accept' => 'image/*,.pdf'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/mds_com_intervencion/update']),
                                                            'maxFileSize' => 1000000,
                                                            'previewFileType' => 'file',
                                                            'initialPreview' => [
                                                                Html::img($model->archivo_adjunto, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                            ],
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'initialCaption' => $model->archivo_adjunto,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                        'pluginEvents' => [
                                                            "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                                            "filereset" => "function() {  }",
                                                        ]
                                                    ]);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-info" href="index.php?r=mds_cor_intervencion/index">Volver </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(
    "
    $('#btn_pui').click(function(){        
    var dni_campo = $('#txtDNI').val();        
    window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
    
    });
    "
);
?>

<script>
    function cargarLocalidades() {
        if ($("#cmb_provincia").val()) {
            $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {
                $("select#idlocalidad").html(data);
                $("#idlocalidad").val($("#idLocalidadSelected").val()).trigger("change");
                $("#idlocalidad").prop("disabled", false);
            });
        } else {
            $("#idlocalidad").val(null).trigger("change");
            $("#idlocalidad").prop("disabled", true);
        }
    }
</script>