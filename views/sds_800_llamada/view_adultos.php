<?php

use app\models\Sds_800_am_recreacion;
use app\models\Sds_800_atencion_am;
use app\models\Sds_800_llamada;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
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
    <h2>Detalle Ingreso de Situación a Adultos Mayores N°: <?php echo $model->idllamada; ?></h2>

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
        } ?></h5>
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

                        <div class="col-md-6">
                            <h5><b>Fecha de Atención: </b>
                                <?php echo date_format(
                                    date_create($model_atencion->fecha_hora),
                                    'd/m/Y H:i'
                                ); ?></h5>
                        </div>

                        <br>
                        <?php echo Collapse::widget([]); ?>
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
                                        <div class="row">
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <!--ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el seleccionar dni-->
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'dni'
                                                    )
                                                    ->textInput([
                                                        'id' => 'txtDNI',
                                                        'disabled' => !$model_atencion->isNewRecord,
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-2" style="padding-top:25px;">
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
                                            <div class="col-md-3">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'apellido'
                                                    )
                                                    ->textInput() ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'nombre'
                                                    )
                                                    ->textInput() ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'telefono'
                                                    )
                                                    ->textInput() ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Localidad</label>
                                                <input type="text" class="form-control" value="<?php echo ($localidadDescripcion) ? $localidadDescripcion : '' ?>" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'telefono_referente'
                                                    )
                                                    ->textInput() ?>
                                            </div>
                                        </div>
                                        <?= $form
                                            ->field(
                                                $model_atencion,
                                                'idpersona'
                                            )
                                            ->hiddenInput()
                                            ->label(false) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion_atencion">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_atencion" href="#atencion">
                                            Detalle de la Atención
                                        </a>
                                    </h4>
                                </div>
                                <div id="atencion" class="accordion-body collapse in">
                                    <div class="panel-body" id="atencion_content">
                                        <div class="row">
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">Motivo de la demanda</label>
                                                <div class="alert alert-detalle" role="alert">
                                                    <p><?php echo $model_atencion->demanda;  ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'atencion_previa'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Estuvo atendido por algun equipo profesional previo a esta llamada?'
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
                                                    ->textarea(['rows' => 2])
                                                    ->label(
                                                        '¿De qué institución?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'profesionales'
                                                    )
                                                    ->textarea(['rows' => 2])
                                                    ->label(
                                                        '¿Qué profesionales?'
                                                    ) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion_vivienda">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vivienda" href="#vivienda">
                                            Datos de la vivienda
                                        </a>
                                    </h4>
                                </div>
                                <div id="vivienda" class="accordion-body collapse in">
                                    <div class="panel-body" id="vivienda_content">
                                        <div class="row">
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'basura'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Servicio de recolección de basura?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'cable'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Servicio de cable?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'internet'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Servicio de Internet?'
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
                                            Situación del Adulto Mayor
                                        </a>
                                    </h4>
                                </div>
                                <div id="situacion" class="accordion-body collapse in">
                                    <div class="panel-body" id="situacion_content">
                                        <div class="row">
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'familiares'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::NO_TIENE =>
                                                            'No tiene',
                                                            Sds_800_atencion_am::FAMILIAR_SIN_VINCULO =>
                                                            'Si tiene y sin vínculos',
                                                            Sds_800_atencion_am::FAMILIAR_CON_VINCULO =>
                                                            'Si tiene y con vínculos',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Tiene red de familiares?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'sociales'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::SOCIAL_NO_TIENE =>
                                                            'No tiene',
                                                            Sds_800_atencion_am::SOCIAL_VECINOS =>
                                                            'Vecinos',
                                                            Sds_800_atencion_am::SOCIAL_ALQUILER =>
                                                            'Propietario de alquiler',
                                                            Sds_800_atencion_am::SOCIAL_OSCPM =>
                                                            'Ref. institucional de OSCPM ',
                                                            Sds_800_atencion_am::SOCIAL_IGLESIA =>
                                                            'Iglesia',
                                                            Sds_800_atencion_am::SOCIAL_OBRA_SOCIAL =>
                                                            'Obra Social',
                                                            Sds_800_atencion_am::SOCIAL_BARRIAL =>
                                                            'Barrial',
                                                            Sds_800_atencion_am::SOCIAL_OTRA =>
                                                            'Otros',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Tiene red de sociales?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'sociales_detalle'
                                                    )
                                                    ->textarea(['rows' => 2])
                                                    ->label('¿Cúal?') ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'emergente'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RED_FAMILIAR =>
                                                            'Red Familiar',
                                                            Sds_800_atencion_am::RED_SOCIAL =>
                                                            'Red Social',
                                                            Sds_800_atencion_am::RED_OTRO =>
                                                            'Otra',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        'Si le sucede algún emergente, ¿A quién acudo de lo antes mencionado?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'emergente_detalle'
                                                    )
                                                    ->textarea(['rows' => 2])
                                                    ->label('Detalle') ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'psicologico'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Se encuentra realizando tratamiento psicológico?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'psiquiatrico'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Se encuentra realizando tratamiento psiquiátrico?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'administra_dinero'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Administra su dinero?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'detalle_dinero'
                                                    )
                                                    ->textarea(['rows' => 2])
                                                    ->label(
                                                        'En caso de no, ¿Quién lo administra?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'plan'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Recibe algun PLAN o PROGRAMA del estado provincial?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'detalle_plan'
                                                    )
                                                    ->textarea(['rows' => 2])
                                                    ->label(
                                                        'En caso afirmativo, ¿Cuál?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'centro'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Le gustaría participar en algún centro de personas mayores?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'recreacion'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Participa de alguna actividad lúdico-recreativa?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <b>¿En que actividad o actividades le gustaría participar?</b>
                                        <div class="row">
                                            <?php
                                            $tipo_recreacion = Sds_com_configuracion::getConfiguracionesActivas(
                                                Sds_com_configuracion_tipo::TIPO_0800_RECREACION
                                            );
                                            $atencion_recre = Sds_800_am_recreacion::find()
                                                ->where([
                                                    'idatencionam' =>
                                                    $model_atencion->idllamada,
                                                ])
                                                ->all();
                                            foreach ($tipo_recreacion
                                                as $tipo_re) {
                                                $checked = '';
                                                foreach ($atencion_recre
                                                    as $ate_recre) {
                                                    if (
                                                        $ate_recre->recreacion ==
                                                        $tipo_re->idconfiguracion
                                                    ) {
                                                        $checked = 'checked';
                                                        break;
                                                    }
                                                }
                                                echo "<div class='col-md-4'>";
                                                echo '<div class="form-group ">' .
                                                    '<label>
                                                            <input type="checkbox" tabindex="1" name="Sds_ris_risneu[tipo_re][]" value=' . $tipo_re->idconfiguracion .
                                                    ' ' . $checked . '> ' .
                                                    $tipo_re->descripcion .
                                                    '</label>
                                                     </div>';
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion_evaluacion">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_evaluacion" href="#evaluacion">
                                            Evaluacíon Funcional
                                        </a>
                                    </h4>
                                </div>
                                <div id="evaluacion" class="accordion-body collapse in">
                                    <div class="panel-body" id="evaluacion_content">
                                        <div class="row">
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'orientado'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
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
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'dependiente'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::TOTALMENTE_DEPENDIENTE =>
                                                            'Totalmente dependiente',
                                                            Sds_800_atencion_am::SEMI_DEPENDIENTE =>
                                                            'Dependiente en algunas o varias actividades',
                                                            Sds_800_atencion_am::INDEPENDIENTE =>
                                                            'Independiente',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Es dependiente o independiente?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'intoxicado'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Se encuentra intoxicado?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'delirios'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Presenta delirios y/o alucinaciones?'
                                                    ) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'violentado'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
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
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field(
                                                        $model_atencion,
                                                        'expresion'
                                                    )
                                                    ->dropDownList(
                                                        [
                                                            Sds_800_atencion_am::RESPUESTA_SIN_DATOS =>
                                                            'Sin Datos',
                                                            Sds_800_atencion_am::RESPUESTA_SI =>
                                                            'Si',
                                                            Sds_800_atencion_am::RESPUESTA_NO =>
                                                            'No',
                                                        ],
                                                        [
                                                            'prompt' =>
                                                            '-- Seleccione una opción --',
                                                        ]
                                                    )
                                                    ->label(
                                                        '¿Se expresa de manera clara?'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">Observaciones</label>
                                                <div class="alert alert-detalle" role="alert">
                                                    <p><?php echo $model_atencion->observaciones;  ?></p>
                                                </div>
                                            </div>
                                        </div>


                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="" style="display:<?= $model_atencion->archivo_seguridad
                                                                                    ? 'block'
                                                                                    : 'none;' ?>">Archivo de Seguridad</label>
                                                <div class="text-center">
                                                    <div style="display:<?= $model_atencion->archivo_seguridad
                                                                            ? 'block'
                                                                            : 'none; text-align: center' ?>">
                                                        <!-- Valida si es pdf -->
                                                        <?php if (
                                                            stripos(
                                                                $model_atencion->archivo_seguridad,
                                                                'application/pdf;base64,'
                                                            ) != false
                                                        ) : ?>
                                                            <div class="row">
                                                                <object width="90%" height="500px" type="application/pdf" data="<?php echo $model_atencion->archivo_seguridad; ?>">
                                                                    <p>Archivo Adjunto no disponible.</p>
                                                                </object>
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="row" style="max-height:500px">
                                                                <div class='col-md-12' align="center" ;> <br>
                                                                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:100%;' id='base64image' src='<?php echo $model_atencion->archivo_seguridad; ?>' />
                                                                    <br>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="row" style="margin-top:2%; text-align: center;">
                                                            <?= Html::a(
                                                                'Ampliar',
                                                                $model_atencion->archivo_seguridad,
                                                                [
                                                                    'target' =>
                                                                    '_blank',
                                                                    'data-pjax' =>
                                                                    '0',
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
                                            <div class="col-md-6">
                                                <label for="" style="display:<?= $model_atencion->archivo_salud
                                                                                    ? 'block'
                                                                                    : 'none;' ?>">Archivo de Salud</label>
                                                <div class="text-center">
                                                    <div class="" style="display:<?= $model_atencion->archivo_salud
                                                                                        ? 'block'
                                                                                        : 'none; text-align: center' ?>">
                                                        <!-- Valida si es pdf -->
                                                        <?php if (
                                                            stripos(
                                                                $model_atencion->archivo_salud,
                                                                'application/pdf;base64,'
                                                            ) != false
                                                        ) : ?>
                                                            <div class="row">
                                                                <object width="90%" height="500px" type="application/pdf" data="<?php echo $model_atencion->archivo_salud; ?>">
                                                                    <p>Archivo Adjunto no disponible.</p>
                                                                </object>
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="row" style="max-height:500px">
                                                                <div class='col-md-12' align="center" ;> <br>
                                                                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:100%;' id='base64image' src='<?php echo $model_atencion->archivo_salud; ?>' />
                                                                    <br>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="row" style="margin-top:2%; text-align: center;">
                                                            <?= Html::a(
                                                                'Ampliar',
                                                                $model_atencion->archivo_salud,
                                                                [
                                                                    'target' =>
                                                                    '_blank',
                                                                    'data-pjax' =>
                                                                    '0',
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
                                        <?= $form
                                            ->field(
                                                $model_atencion,
                                                'idusuario'
                                            )
                                            ->hiddenInput()
                                            ->label(false) ?>

                                        <?= $form
                                            ->field(
                                                $model_atencion,
                                                'idllamada'
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
                                        <div class="col-md-4">
                                            <label class="form-label">Nacionalidad</label>
                                            <input type="text" class="form-control" value="<?php echo ($nacionalidadDescripcion) ? $nacionalidadDescripcion->descripcion : '' ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
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
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'vinculo')
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
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle_derivada" href="#detalle_derivacion">
                                        Detalle de Derivación
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_derivacion" class="accordion-body collapse in">
                                <div class="panel-body" id="detalle_derivacion_content">
                                    <div class="row">
                                        <div class="col-md-3">
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
                    <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                </div>
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