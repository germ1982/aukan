<?php

use app\models\Sds_800_atencion;
use app\models\Sds_800_atencion_familia;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\bootstrap\Collapse;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_familia */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Atención / Intervención  Familia';
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
<div class="sds-800-atencion-familia-form">
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
                            <div id="atencion" class="accordion-body collapse in">
                                <div class="panel-body" id="atencion_content">
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
                                                        Sds_800_atencion_familia::LUGAR_ADMISION =>
                                                        'Familia - Admisión',
                                                        Sds_800_atencion_familia::LUGAR_FAMILIA =>
                                                        'Familia - Ley 2302',
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
                                                    '$.post("index.php?r=sds_800_atencion_familia/validar_dni&dni=' .
                                                        '"+$("#txtDNI").val()+"&idllamada=' .
                                                        $model->idllamada .
                                                        '", function(data){                                                                                                              
                                                        var data = $.parseJSON(data);
                                                        $("#idcomponentequemuestramensaje").val(data.mensaje);
                                                        $("#sds_800_atencion_familia-apellido").val(data[1].apellido);
                                                        $("#sds_800_atencion_familia-nombre").val(data[1].nombre);
                                                        $("#sds_800_atencion_familia-idpersona").val(data[1].idpersona);     
                                                        if(data.length > 0) {
                                                            $("#btn_risneu").show();
                                                        } else {
                                                            $("#btn_risneu").hide();
                                                            $("#btn_risneu").prop("href", "");
                                                        }
                                                    });',
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
                                        <div class="row">
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje"></div>
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
                                            <?= $form->field($model, 'localidad')->widget(Select2::class, [
                                                'data' => $listLocalidades,
                                                'options' => [
                                                    'placeholder' => 'Seleccionar ...',
                                                    'id' => 'cmb_localidad'
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label("Localidad");
                                            ?>
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
                                        <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje1"></div>
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
                                                ->label('Fecha de Nacimiento');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'nacionalidad1')
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
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion::getConfiguracionesActivas(
                                                            Sds_com_configuracion_tipo::TIPO_GENERO,
                                                            false
                                                        ),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),
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
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion::getConfiguracionesActivas(
                                                            Sds_com_configuracion_tipo::TIPO_PARENTEZCO,
                                                            true
                                                        ),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),
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
                                            <?= $form->field($model, 'localidad1')->widget(Select2::class, [
                                                'data' => $listLocalidades,
                                                'options' => [
                                                    'placeholder' => 'Seleccionar ...',
                                                    'id' => 'cmb_localidad1'
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label("Localidad");
                                            ?>
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

                    <div class="panel-group" id="accordion_institucionalizado">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_institucionalizado" href="#institucionalizado">
                                        Datos de Niño, Niña y/o Adolescente Institucionalizado
                                    </a>
                                </h4>
                            </div>
                            <div id="institucionalizado" class="accordion-body collapse in">
                                <div class="panel-body" id="institucionalizado_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'alojado')
                                                ->textInput([
                                                    'maxlength' => true,
                                                ])
                                                ->label(
                                                    'Último lugar en el que estuvo alojado'
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">

                                            <?= $form
                                                ->field($model, 'hogar')
                                                ->textInput([
                                                    'maxlength' => true,
                                                ])
                                                ->label(
                                                    'Hogar de referencia'
                                                ) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            if (!$model->hora) {
                                                $model->hora = date(
                                                    'H:i',
                                                    strtotime(
                                                        str_replace(
                                                            '/',
                                                            '-',
                                                            $model->dia_hora
                                                        )
                                                    )
                                                );
                                            }
                                            if (!$model->dia_hora) {
                                                $model->dia_hora = date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        str_replace(
                                                            '/',
                                                            '-',
                                                            $model->dia_hora
                                                        )
                                                    )
                                                );
                                            }

                                            echo $form
                                                ->field($model, 'dia_hora')
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
                                                            'fecha_entrega',
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
                                                    'Día de la salida sin autorización'
                                                );
                                            ?>
                                        </div>
                                        <div class="col-lg-6">
                                            <?= $form
                                                ->field($model, 'hora')
                                                ->widget(
                                                    TimePicker::class,
                                                    [
                                                        'options' => [
                                                            'id' => 'hora',
                                                            'class' =>
                                                            'form-control input-sm',
                                                        ],
                                                        'pluginOptions' => [
                                                            'showSeconds' => false,
                                                            'showMeridian' => false,
                                                            'minuteStep' => 15,
                                                        ],
                                                    ]
                                                )
                                                ->label(
                                                    'Hora de la salida sin autorización'
                                                ) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'operador')
                                                ->textInput([
                                                    'maxlength' => true,
                                                ])
                                                ->label('Operador de turno') ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'equipo_tecnico'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                ])
                                                ->label(
                                                    'Equipo técnico y profesionales del hogar'
                                                ) ?>
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
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'edad')
                                                ->textInput()
                                                ->label(
                                                    'Edad que dice tener'
                                                ) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'sabe_leer')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'nivel_estudio')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::ESTUDIO_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::ESTUDIO_PRIMARIO_INCOMPLETO =>
                                                        'Primario Incompleto',
                                                        Sds_800_atencion_familia::ESTUDIO_PRIMARIO_COMPLETO =>
                                                        'Primario Completo',
                                                        Sds_800_atencion_familia::ESTUDIO_SECUNDARIO_INCOMPLETO =>
                                                        'Secundario Incompleto',
                                                        Sds_800_atencion_familia::ESTUDIO_SECUNDARIO_COMPLETO =>
                                                        'Secundario Completo',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    'Máximo nivel de estudio alcanzado'
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'establecimiento'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->nivel_estudio ==
                                                        0,
                                                ])
                                                ->label(
                                                    'Nombre del establecimiento educativo'
                                                ) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'trabaja')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Efectúa algún tipo de trabajo?'
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'tipo_trabajo')
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->trabaja == 0,
                                                ])
                                                ->label(
                                                    '¿Qué tipo de trabajo?'
                                                ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'atendido')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Estuvo atendiendo algún equipo Técnico al grupo Familiar en este tiempo?'
                                                ) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'institucion')
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->atendido == 0,
                                                ])
                                                ->label(
                                                    '¿De qué institución?'
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'nombre_profesionales'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->atendido == 0,
                                                ])
                                                ->label(
                                                    'Nombre de el o los profesionales'
                                                ) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'beneficio_social'
                                                )
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Posee el grupo conviviente del NNy/oA algún beneficio Social?'
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'area_beneficio'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->beneficio_social ==
                                                        0,
                                                ])
                                                ->label(
                                                    'Área que otorga dicho beneficio'
                                                ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'centro_salud')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Concurre el NNy/oA a algún centro de salud?'
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'nombre_centro_salud'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->centro_salud ==
                                                        0,
                                                ])
                                                ->label(
                                                    'Especificar Profesional y/o Institución'
                                                ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'obra_social')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Posee obra social?'
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'nombre_obra_social'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->obra_social ==
                                                        0,
                                                ])
                                                ->label('¿Cúal?') ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'tratamiento_medico'
                                                )
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Se encuentra bajo tratamiento médico/psicológico y/o psiquiátrico?'
                                                ) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'tratamiento_institucion'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->tratamiento_medico ==
                                                        0,
                                                ])
                                                ->label(
                                                    'Especificar Profesional y/o Institución'
                                                ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'orientado')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Se encuentra orientado en tiempo y espacio?'
                                                ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'intoxicado')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Se encuentra Intoxicado?'
                                                ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'violentado')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion_familia::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion_familia::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion_familia::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                )
                                                ->label(
                                                    '¿Se encuentra violentado?'
                                                ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" id="plan_accion_texto_container">
                                            <?= $form->field($model, 'plan_accion')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'plan_accion_texto',
                                                ],
                                            ])->label("CONSIDERACIONES/PLAN DE ACCIÓN") ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php if (
                                                $model->archivo_adjunto == null
                                            ) {
                                                echo $form
                                                    ->field(
                                                        $model,
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
                                                                $model->archivo_adjunto,
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
                                                        $model,
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
                                                                "function() { console.log('fileclear'); $('#borrar').val(true);}",
                                                                'filereset' =>
                                                                'function() {  }',
                                                            ],
                                                        ]
                                                    );
                                            } ?>
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'borrar_adjunto'
                                                )
                                                ->hiddenInput([
                                                    'id' => 'borrar',
                                                ])
                                                ->label(false) ?>
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
                                <?php if (!Yii::$app->request->isAjax) { ?>
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

    $('#sds_800_atencion_familia-nivel_estudio').change(function(){        
        $('#sds_800_atencion_familia-establecimiento').prop('disabled', $('#sds_800_atencion_familia-nivel_estudio option:selected').val()<=0);
    });

    $('#sds_800_atencion_familia-trabaja').change(function(){        
        $('#sds_800_atencion_familia-tipo_trabajo').prop('disabled', $('#sds_800_atencion_familia-trabaja option:selected').val()!=1);
    });

    $('#sds_800_atencion_familia-atendido').change(function(){        
        $('#sds_800_atencion_familia-institucion').prop('disabled', $('#sds_800_atencion_familia-atendido option:selected').val()!=1);
        $('#sds_800_atencion_familia-nombre_profesionales').prop('disabled', $('#sds_800_atencion_familia-atendido option:selected').val()!=1);
    });

    $('#sds_800_atencion_familia-centro_salud').change(function(){        
        $('#sds_800_atencion_familia-nombre_centro_salud').prop('disabled', $('#sds_800_atencion_familia-centro_salud option:selected').val()!=1);
    });

    $('#sds_800_atencion_familia-obra_social').change(function(){        
        $('#sds_800_atencion_familia-nombre_obra_social').prop('disabled', $('#sds_800_atencion_familia-obra_social option:selected').val()!=1);
    });

    $('#sds_800_atencion_familia-tratamiento_medico').change(function(){        
        $('#sds_800_atencion_familia-tratamiento_institucion').prop('disabled', $('#sds_800_atencion_familia-tratamiento_medico option:selected').val()!=1);
    });


    $('#sds_800_atencion_familia-beneficio_social').change(function(){        
        $('#sds_800_atencion_familia-area_beneficio').prop('disabled', $('#sds_800_atencion_familia-beneficio_social option:selected').val()!=1);
    });

    $('#btn_risneu').hide();
    $('#btn_risneu').click(function(){ 
        var dni = $('#txtDNI').val();       
        var llamada = $('#sds_800_atencion_familia-idllamada').val()
        getIdRisneu(dni, llamada);
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
        const plan_accion_texto =  $('#plan_accion_texto').val();
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

    function getIdLocalidad(localidad) {
        $.post("index.php?r=sds_800_atencion/get_id_localidad&localidad=" + localidad, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                return "";
            } else {
                $("#sds_800_atencion-localidad1").val(data['idlocalidad']);
            }
        });
    }

    function getIdRisneu(dni, llamada) {
        $.post("index.php?r=sds_800_atencion_familia/get_id_risneu&dni=" + dni + "&llamada=" + llamada, function(data) {
            data = $.parseJSON(data);
            window.open('<?php echo Url::base(); ?>/index.php?r=sds_ris_risneu%2Fupdate&finalizar=0&dni=' + dni + '&id=' + data, '_blank');
        });
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
                        $("#sds_800_atencion_familia-idpersona_referente").val(data[0]['idpersona']);
                        $("#sds_800_atencion_familia-nombre1").val(data[0]['nombre']);
                        $("#sds_800_atencion_familia-apellido1").val(data[0]['apellido']);
                        $("#fecha_nacimiento1").val(formatearFecha(data[0]['fecha_nacimiento']));
                        $("#sds_800_atencion_familia-nacionalidad1").val(data[0]['nacionalidad']);
                        $("#sds_800_atencion_familia-sexo1").val(data[0]['genero']);
                        if (data.length > 1) {
                            $("#sds_800_atencion_familia-localidad1").val(data[1]['idlocalidad']);
                        } else {
                            $("#sds_800_atencion_familia-localidad1").val("");
                        }
                        $('#txt_mensaje').html("");
                        habilitar_controles1();
                    }
                });
                $('#txt_mensaje1').html("");
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
                    $("#sds_800_atencion_familia-nombre1").val(corregir_palabra(nombre1));
                    $("#sds_800_atencion_familia-apellido1").val(corregir_palabra(apellido1));
                    $("#fecha_nacimiento1").val(fecha_nacimiento1);
                    $("#sds_800_atencion_familia-nacionalidad1").val('');
                    $("#sds_800_atencion_familia-sexo1").val('');
                    $("#sds_800_atencion_familia-localidad1").val('');
                    //$("#renaper_foto1").attr("src", foto1);
                    $('#txt_mensaje1').html("");
                    habilitar_controles1();
                }
            }
        });
    }

    function limpiarDatos1() {
        habilitar_controles1();
        $("#sds_800_atencion_familia-nombre1").val('');
        $("#sds_800_atencion_familia-apellido1").val('');
        $("#fecha_nacimiento1").val('');
        $("#sds_800_atencion_familia-nacionalidad1").val('');
        $("#sds_800_atencion_familia-sexo1").val('');
        $("#sds_800_atencion_familia-telefono1").val("");
        $("#sds_800_atencion_familia-domicilio1").val("");
        $("#sds_800_atencion_familia-localidad1").val("");
        $("#sds_800_atencion_familia-idpersona_referente").val('0');
    }


    function getIdLocalidad(localidad) {
        $.post("index.php?r=sds_800_atencion/get_id_localidad&localidad=" + localidad, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                return "";
            } else {
                $("#sds_800_atencion-localidad").val(data['idlocalidad']);
            }
        });
    }


    function habilitar_controles1() {
        $("#sds_800_atencion_familia-nombre1").prop("disabled", false);
        $("#sds_800_atencion_familia-apellido1").prop("disabled", false);
        $("fecha_nacimiento1").prop("disabled", false);
        $("#sds_800_atencion_familia-nacionalidad1").prop("disabled", false);
        $("#sds_800_atencion_familia-sexo1").prop("disabled", false);
        $("#sds_800_atencion_familia-localidad1").prop("disabled", false);
        $("#sds_800_atencion_familia-telefono1").prop("disabled", false);
        $("#sds_800_atencion_familia-parentezco").prop("disabled", false);
        $("#sds_800_atencion_familia-domicilio1").prop("disabled", false);
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