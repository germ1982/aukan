<?php

use app\models\Sds_800_atencion;
use app\models\Sds_800_atencion_familia;
use app\models\Sds_800_llamada;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use pigolab\locationpicker\CoordinatesPicker;
use yii\bootstrap\Collapse;
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
<header class="page-header">
    <h2>Detalle Ingreso de Situación a Familia N°: <?php echo $model->idllamada; ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Llamada 0800 Detalle</span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="alert alert-info">
    <h5 style="margin: 0"><b>ESTADO ACTUAL: </b>
        <?php switch ($model->estado) {
            case Sds_800_llamada::ESTADO_PENDIENTE:
                echo 'Pendiente de evaluación';
                break;
            case Sds_800_llamada::ESTADO_NC:
                echo 'No Corresponde';
                break;
            case Sds_800_llamada::ESTADO_DERIVADA:
                echo 'Derivada';
                break;
            case Sds_800_llamada::ESTADO_ATENDIDA:
                echo 'Atendida';
                break;
            case Sds_800_llamada::ESTADO_CERRADA:
                echo 'Cerrada';
                break;
            case Sds_800_llamada::ESTADO_DESPEJADA:
                echo 'Despejada';
                break;
        } ?>
    </h5>
</div>
<?php if ($model_atencion != null) : ?>
    <div class="sds-800-atencion-view">
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
                                                        Sds_800_atencion_familia::LUGAR_ADMISION =>
                                                        'Familia - Admisión',
                                                        Sds_800_atencion_familia::LUGAR_FAMILIA =>
                                                        'Familia - Ley 2302',
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

                                        <!-- ?= $form->field($model, 'idpersona')->textInput(['maxlength' => true]) ?-->
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
                                                        'disabled' => true,
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-3" style="padding-top:25px;">
                                                <?php echo Html::a(
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

                                            <div class="col-md-4 form-group">
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
                                                        'disabled' => true,
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-3" style="padding-top:25px;">
                                                <?php echo Html::a(
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
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'alojado'
                                                    )
                                                    ->textInput([
                                                        'maxlength' => true,
                                                    ])
                                                    ->label(
                                                        'Último lugar en el que estuvo alojado'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-4">

                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'hogar'
                                                    )
                                                    ->textInput([
                                                        'maxlength' => true,
                                                    ])
                                                    ->label(
                                                        'Hogar de referencia'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'dia_hora'
                                                    )
                                                    ->widget(
                                                        DateTimePicker::class,
                                                        [
                                                            'name' =>
                                                            'check_issue_date',
                                                            'language' => 'es',
                                                            'readonly' => false,
                                                            //'type' => DateTimePicker::TYPE_INPUT,
                                                            //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), le saco la opción de remover
                                                            'layout' => !$model_atencion->isNewRecord
                                                                ? '{picker}{input}'
                                                                : '{picker}{input}{remove}',
                                                            'options' => [
                                                                'id' =>
                                                                'dia_hora',
                                                                'class' =>
                                                                'form-control input-md',
                                                                //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito la selección del date.
                                                                'disabled' => !$model_atencion->isNewRecord,
                                                            ],
                                                            'pluginOptions' => [
                                                                'value' => null,
                                                                'format' =>
                                                                'dd-mm-yyyy hh:ii',
                                                                'endDate' => date(
                                                                    'd/m/Y'
                                                                ),
                                                                'todayHighlight' => true,
                                                                'autoclose' => true,
                                                            ],
                                                        ]
                                                    )
                                                    ->label(
                                                        'Día y hora de la salida sin autorización'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'operador'
                                                    )
                                                    ->textInput([
                                                        'maxlength' => true,
                                                    ])
                                                    ->label(
                                                        'Operador de turno'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
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
                                            Detalle de la Atención
                                        </a>
                                    </h4>
                                </div>
                                <div id="situacion" class="accordion-body collapse in">
                                    <div class="panel-body" id="situacion_content">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'edad'
                                                    )
                                                    ->textInput()
                                                    ->label(
                                                        'Edad que dice tener'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'sabe_leer'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
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
                                                    ->field(
                                                        $model_atencion,
                                                        'nivel_estudio'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::ESTUDIO_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::ESTUDIO_PRIMARIO_INCOMPLETO =>
                                                            'Primario Incompleto',
                                                            Sds_800_atencion::ESTUDIO_PRIMARIO_COMPLETO =>
                                                            'Primario Completo',
                                                            Sds_800_atencion::ESTUDIO_SECUNDARIO_INCOMPLETO =>
                                                            'Secundario Incompleto',
                                                            Sds_800_atencion::ESTUDIO_SECUNDARIO_COMPLETO =>
                                                            'Secundario Completo',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                            'id' =>
                                                            'cmb_estudio',
                                                        ]
                                                    )
                                                    ->label(
                                                        'Máximo nivel de estudio alcanzado'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'establecimiento'
                                                    )
                                                    ->textarea([
                                                        'rows' => 2,
                                                        'disabled' => true,
                                                    ])
                                                    ->label(
                                                        'Nombre del establecimiento educativo'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'trabaja'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                            'id' =>
                                                            'cmb_trabaja',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Efectúa algún tipo de trabajo?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'tipo_trabajo'
                                                    )
                                                    ->textarea([
                                                        'rows' => 2,
                                                        'disabled' => true,
                                                    ])
                                                    ->label(
                                                        '¿Qué tipo de trabajo?'
                                                    ) ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'atendido'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                            'id' =>
                                                            'cmb_atendido',
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
                                                    ->field(
                                                        $model_atencion,
                                                        'institucion'
                                                    )
                                                    ->textarea([
                                                        'rows' => 2,
                                                        'disabled' => true,
                                                    ])
                                                    ->label(
                                                        '¿De qué institución?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'nombre_profesionales'
                                                    )
                                                    ->textarea([
                                                        'rows' => 2,
                                                        'disabled' => true,
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
                                                        $model_atencion,
                                                        'beneficio_social'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                            'id' =>
                                                            'cmb_beneficio',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Posee el grupo conviviente del NNy/oA algún beneficio Social?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'area_beneficio'
                                                    )
                                                    ->textarea([
                                                        'rows' => 2,
                                                        'disabled' => true,
                                                    ])
                                                    ->label(
                                                        'Área que otorga dicho beneficio'
                                                    ) ?>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'centro_salud'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                            'id' =>
                                                            'cmb_centro',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Concurre el NNy/oA a algún centro de salud?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'nombre_centro_salud'
                                                    )
                                                    ->textarea([
                                                        'rows' => 2,
                                                        'disabled' => true,
                                                    ])
                                                    ->label(
                                                        'Especificar Profesional y/o Institución'
                                                    ) ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'obra_social'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                            'id' => 'cmb_obra',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Posee obra social?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'nombre_obra_social'
                                                    )
                                                    ->textarea([
                                                        'rows' => 2,
                                                        'disabled' => true,
                                                    ])
                                                    ->label('¿Cúal?') ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'tratamiento_medico'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                            'id' =>
                                                            'cmb_medico',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Se encuentra bajo tratamiento médico/psicológico y/o psiquiátrico?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'tratamiento_institucion'
                                                    )
                                                    ->textarea([
                                                        'rows' => 2,
                                                        'disabled' => true,
                                                    ])
                                                    ->label(
                                                        'Especificar Profesional y/o Institución'
                                                    ) ?>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'orientado'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
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
                                                    ->field(
                                                        $model_atencion,
                                                        'intoxicado'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
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
                                                    ->field(
                                                        $model_atencion,
                                                        'violentado'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion::RESPUESTA_NO =>
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
                                            <div class="col-md-12">
                                                <label class="control-label">CONSIDERACIONES/PLAN DE ACCIÓN</label>
                                                <div class="alert alert-detalle" role="alert">
                                                    <p><?php echo $model_atencion->plan_accion;  ?></p>
                                                </div>
                                            </div>

                                        </div>
                                        <label style="display:<?= $model_atencion->archivo_adjunto
                                                                    ? 'block'
                                                                    : 'none;' ?>" for="">Archivo de Salud</label>
                                        <div class="row text-center">
                                            <div class="col-md-6" style="display:<?= $model_atencion->archivo_adjunto
                                                                                        ? 'block'
                                                                                        : 'none; text-align: center' ?>">
                                                <!-- Valida si es pdf -->
                                                <?php if (
                                                    stripos(
                                                        $model_atencion->archivo_adjunto,
                                                        'application/pdf;base64,'
                                                    ) != false
                                                ) : ?>
                                                    <div class="row">
                                                        <object width="90%" height="500px" type="application/pdf" data="<?php echo $model_atencion->archivo_adjunto; ?>">
                                                            <p>Archivo Adjunto no disponible.</p>
                                                        </object>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="row" style="max-height:500px">
                                                        <div class='col-md-12' align="center" ;> <br>
                                                            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:70%;' id='base64image' src='<?php echo $model_atencion->archivo_adjunto; ?>' />
                                                            <br>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="row" style="margin-top:2%; text-align: center;">
                                                    <?= Html::a(
                                                        'Ampliar',
                                                        $model_atencion->archivo_adjunto,
                                                        [
                                                            'target' =>
                                                            '_blank',
                                                            'data-pjax' => '0',
                                                            'class' =>
                                                            'btn btn-success',
                                                            'style' =>
                                                            'width:80%',
                                                        ]
                                                    ) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                ->field($model_atencion, 'idpersona_referente')
                                ->hiddenInput()
                                ->label(false) ?>
                            <?php ActiveForm::end(); ?>
                </section>
            </div>
        </div>

    </div>
<?php endif; ?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="sds-800-llamada-form">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <h5><b>Fecha de Llamada: </b>
                                <?php echo date_format(
                                    date_create($model->fecha_hora),
                                    'd/m/Y H:i'
                                ); ?></h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <h5><b>Atendió: </b>
                                <?php echo $model->usuario->nombre .
                                    ' ' .
                                    $model->usuario->apellido; ?></h5>
                        </div>
                    </div>
                    <br>
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
                                            <?= $form
                                                ->field($model, 'dni')
                                                ->textInput([
                                                    'id' => 'txtDNI',
                                                    'disabled' => true,
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
                                                ->textInput([
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <?= $form
                                                ->field($model, 'domicilio')
                                                ->textInput([
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Localidad</label>
                                            <input type="text" class="form-control" value="<?php echo ($model->localidad) ? $model->localidad : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'institucion')
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'vinculo')
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Profesional Interviniente</label>
                                            <input type="text" class="form-control" value="<?php echo $model->profesional_interviniente0 ? $model->profesional_interviniente0->apellido . ", " . $model->profesional_interviniente0->nombre : " " ?>" readonly>

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
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_situacion_detalle" href="#detalle_situacion">
                                        Detalle de la Situación
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_situacion" class="accordion-body collapse in">
                                <div class="panel-body" id="detalle_situacion_content">
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
                                                ->textInput([
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'afectado_nombre'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'afectado_apodo'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'afectado_tratamiento'
                                                )
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
                                                        ->field(
                                                            $model,
                                                            'latitud'
                                                        )
                                                        ->textInput([
                                                            'disabled' =>
                                                            'true',
                                                        ]) ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?= $form
                                                        ->field(
                                                            $model,
                                                            'longitud'
                                                        )
                                                        ->textInput([
                                                            'disabled' =>
                                                            'true',
                                                        ]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form
                                                        ->field(
                                                            $model,
                                                            'direccion'
                                                        )
                                                        ->textInput([
                                                            'maxlength' => true,
                                                            'readonly' => true,
                                                        ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo CoordinatesPicker::widget(
                                                [
                                                    'model' => $model,
                                                    'attribute' =>
                                                    'coordenadas',
                                                    'key' =>
                                                    'AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg', // require , Put your google map api key
                                                    'valueTemplate' =>
                                                    '{latitude},{longitude}', // Optional , this is default result format
                                                    'options' => [
                                                        'style' =>
                                                        'width: 100%; height: 405px', // map canvas width and height
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
                                                        'streetViewControl' => true, // Enable Street View Control
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
                                                        'markerDraggable' => 0,
                                                        'addressFormat' =>
                                                        'street_number',
                                                    ],
                                                ]
                                            ); ?>
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
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle_derivacion" href="#detalle_derivacion">
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
                                                ->field(
                                                    $model,
                                                    'derivacion_referente'
                                                )
                                                ->textInput([
                                                    'disabled' => true,
                                                ]) ?>
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
                    <?php ActiveForm::end(); ?>
                </div>
                <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
            </div>
        </section>
    </div>
</div>
<?php $this->registerJs(
    "$(document).ready(function() {                
        datos_derivacion();
    });
    "
); ?>
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