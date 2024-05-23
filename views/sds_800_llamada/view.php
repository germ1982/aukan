<?php

use app\models\Sds_800_atencion;
use app\models\Sds_800_llamada;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use pigolab\locationpicker\CoordinatesPicker;
use yii\web\JsExpression;
use yii\bootstrap\Collapse;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion */

$obj_atencion = Sds_800_atencion::find()
    ->where(['idllamada' => $model->idllamada])
    ->one();
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
    <h2>Detalle Llamada 0800 Situación de Calle N°: <?php echo $model->idllamada ?></h2>

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
                echo "Pendiente de evaluación";
                break;
            case Sds_800_llamada::ESTADO_NC:
                echo "No Corresponde";
                break;
            case Sds_800_llamada::ESTADO_DERIVADA:
                echo "Derivada";
                break;
            case Sds_800_llamada::ESTADO_ATENDIDA:
                echo "Atendida";
                break;
            case Sds_800_llamada::ESTADO_CERRADA:
                echo "Cerrada";
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
                        <?php $form = ActiveForm::begin(['disabled' => true]); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <h5><b>Fecha de Atención: </b>
                                    <?php echo date_format(date_create($model_atencion->fecha_hora), 'd/m/Y H:i') ?></h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <h5><b>Atendió: </b>
                                    <?php echo $model_atencion->idusuario0->nombre . ' ' . $model_atencion->idusuario0->apellido ?></h5>
                            </div>
                        </div>
                        <br>
                        <div class="panel-group" id="accordion_atencion">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_atencion" href="#atencion">
                                            Persona en Situación de Calle
                                        </a>
                                    </h4>
                                </div>
                                <div id="atencion" class="accordion-body collapse in">
                                    <div class="panel-body" id="atencion_content">
                                        <?php if ($model_atencion->dni !== null) : ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form->field($model_atencion, 'dni')->textInput(["disabled" => true]) ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php
                                                    if ($model_atencion->fecha_nacimiento != null) {
                                                        $model_atencion->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model_atencion->fecha_nacimiento)));
                                                    }
                                                    echo $form->field($model_atencion, 'fecha_nacimiento')->widget(DatePicker::class, [
                                                        'name' => 'check_issue_date',
                                                        'language' => 'es',
                                                        'readonly' => false,
                                                        'layout' => '{picker}{input}{remove}',
                                                        'options' => [
                                                            'id' => 'fecha_nacimiento',
                                                            'class' => 'form-control input-md',
                                                            'disabled' => true
                                                        ],
                                                        'pluginOptions' => [
                                                            'value' => null,
                                                            'format' => 'dd/mm/yyyy',
                                                            'endDate' => date('d/m/Y'),
                                                            'todayHighlight' => true,
                                                            'autoclose' => true,
                                                        ]
                                                    ])->label('Fecha de Nacimiento'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form->field($model_atencion, 'nombre')->textInput() ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model_atencion, 'apellido')->textInput() ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Sexo</label>
                                                    <input type="text" class="form-control" value="<?php echo ($generoModelAtencionDescripcion) ? $generoModelAtencionDescripcion->descripcion : '' ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Nacionalidad</label>
                                                    <input type="text" class="form-control" value="<?php echo ($nacionalidadModelAtencionDescripcion) ? $nacionalidadModelAtencionDescripcion->descripcion : '' ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Localidad</label>
                                                    <input type="text" class="form-control" value="<?php echo ($localidadDescripcion) ? $localidadDescripcion : '' ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model_atencion, 'telefono')->textInput(["disabled" => "true"]) ?>
                                                </div>

                                            </div>
                                        <?php else : ?>
                                            <p><b>Atención:</b> Sin Documento</p>
                                            <?= $form->field($model_atencion, 'persona_datos')->textarea(['rows' => 2]) ?>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>



                        <?php

                        if ($obj_atencion != null) {


                            echo Collapse::widget([]);
                            echo '<div class="panel-group" id="accordion_aspsocial" style="display:';
                            if (
                                $model->estado == Sds_800_llamada::ESTADO_CERRADA ||
                                $model->estado == Sds_800_llamada::ESTADO_DERIVADA ||
                                $model->estado == Sds_800_llamada::ESTADO_ATENDIDA
                            ) {
                                echo  "block";
                            } else {
                                echo "none";
                            }
                            echo '">
                                                                                                              
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_aspsocial" href="#aspsocial">
                                                        Aspectos Sociales
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="aspsocial" class="accordion-body collapse in">
                                                <div class="panel-body" id="atencion_content">                                                                            
                                                    <div class="row">                                    
                                                        <div class="col-md-12">' .

                                "<label class='control-label'>Motivo de situación de calle</label>
                                        <div class='alert alert-detalle' role='alert'>
                                            <p>$obj_atencion->causa_situacion</p>
                                        </div>"

                                . '
                                                        </div>   
                                                    </div>                                                                                                                 
                                                    <div class="row">  
                                                        <div class="col-md-4">' .
                                $form
                                ->field($obj_atencion, "familia")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::FAMILIA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::FAMILIA_TIENE_VINCULO =>
                                        "Si tiene y con vínculos adecuados",
                                        Sds_800_atencion::FAMILIA_TIENE_SIN_VINCULO =>
                                        "Si tiene y sin vínculos adecuados",
                                        Sds_800_atencion::FAMILIA_NO_TIENE =>
                                        "No tiene",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]

                                )
                                ->label("Red social y familiar ")
                                . '
                                                        </div>                                   
                                                        <div class="col-md-4">' .
                                $form
                                ->field($obj_atencion, "tipo_ayuda")
                                ->widget(
                                    Select2::class,
                                    [
                                        "data" => $selectTipoAyuda,
                                        "options" => [
                                            "placeholder" => "Seleccionar tipo ayuda ...",
                                            "id" => "tipo_ayuda",
                                            "disabled" => true
                                        ],
                                        "pluginOptions" => [
                                            "allowClear" => true
                                        ]
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-4">' .
                                $form
                                ->field($obj_atencion, "expectativa_corto_plazo")
                                ->widget(
                                    Select2::class,
                                    [
                                        "data" => $selectExpectativaCortoPlazo,
                                        "options" => [
                                            "placeholder" => "Seleccionar opción ...",
                                            "id" => "expectativa_corto_plazo",
                                            "disabled" => true
                                        ],
                                        "pluginOptions" => [
                                            "allowClear" => true
                                        ]
                                    ]
                                ) . '
                                                        </div>                                                                                                                                                          
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "evaluacion_funcional"
                                )
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::FUNCIONAL_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::FUNCIONAL_DEPENDIENTE =>
                                        "Totalmente Dependiente",
                                        Sds_800_atencion::FUNCIONAL_CASI_DEPENDIENTE =>
                                        "Dependiente en Algunas o Varias Actividades",
                                        Sds_800_atencion::FUNCIONAL_INDEPENDIENTE =>
                                        "Independiente",
                                        Sds_800_atencion::FUNCIONAL_OTRO =>
                                        "Otro",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-8">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "evaluacion_funcional_detalle"
                                )
                                ->textarea([
                                    "rows" => 2,
                                    "disabled" => true

                                ]) . '
                                                        </div>
                                                    </div>

                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>';


                            echo Collapse::widget([]);
                            echo '
                                    <div class="panel-group" id="accordion_habitacional" style="display:';

                            if (
                                $model->estado == Sds_800_llamada::ESTADO_CERRADA ||
                                $model->estado == Sds_800_llamada::ESTADO_DERIVADA ||
                                $model->estado == Sds_800_llamada::ESTADO_ATENDIDA
                            ) {
                                echo  "block";
                            } else {
                                echo "none";
                            }
                            echo '">
                                    
                                   
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_habitacional" href="#habitacional">
                                                    Aspectos Habitacionales
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="habitacional" class="accordion-body collapse in">
                                                <div class="panel-body" id="atencion_content">                                    
                                                        
                                                <div class="row">
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "antiguedad")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::ANTIGUEDAD_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::ANTIGUEDAD_MENOS_1 =>
                                        "menos de 1 años",
                                        Sds_800_atencion::ANTIGUEDAD_1_5 =>
                                        "entre 1 y 5 años",
                                        Sds_800_atencion::ANTIGUEDAD_MAS_5 =>
                                        "mas de 5 años",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                )
                                ->label("Hace cuanto se encuentra en situación de calle? ")
                                . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "ubicacion_anterior"
                                )
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::UBICACION_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::UBICACION_FAMILIAR =>
                                        "En la casa de un familiar",
                                        Sds_800_atencion::UBICACION_CUENTA_PROPIA =>
                                        "Alquilaba por cuenta propia",
                                        Sds_800_atencion::UBICACION_ESTADO =>
                                        "Le alquilaba algún efector del estado",
                                        Sds_800_atencion::UBICACION_OTRO =>
                                        "Otro",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                
                                                        <div class="col-md-6">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "ubicacion_anterior_detalle"
                                )
                                ->textarea([
                                    "rows" => 2,
                                    "disabled" => true

                                ]) . '
                                                        </div>
                
                
                
                                                        
                                                    </div>
                                                   
                                                    
                
                                                    <div class="row">
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "motivo_abandono")
                                ->widget(
                                    Select2::class,
                                    [
                                        "data" => $selectMotivoAbandono,
                                        "options" => [
                                            "placeholder" => "Seleccionar opción ...",
                                            "id" => "motivo_abandono",
                                            "disabled" => true
                                        ],
                                        "pluginOptions" => [
                                            "allowClear" => true
                                        ]
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "atencion_anterior"
                                )
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "atencion_anterior_institucion"
                                )
                                ->textInput([
                                    "maxlength" => true,
                                    "disabled" => true

                                ]) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "atencion_anterior_profesional"
                                )
                                ->textInput([
                                    "maxlength" => true,
                                    "disabled" => true

                                ]) . '
                                                        </div>
                                                    </div>
                
                                                    <div class="row">
                                                        <div class="col-md-4">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "asistencia_estado"
                                )
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                )
                                ->label("Recorrido Institucional")
                                . '
                                                        </div>
                                                        <div class="col-md-8">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "asistencia_estado_detalle"
                                )
                                ->textarea([
                                    "rows" => 2,
                                    "disabled" => true

                                ])
                                ->label("¿Cuál?") . '
                                                        </div>
                                                    </div>
                
                                                </div>                                
                                            </div>
                                        </div>
                                    </div>';



                            echo  Collapse::widget([]);
                            echo '
                                    <div class="panel-group" id="accordion_salud"  style="display:';
                            if (
                                $model->estado == Sds_800_llamada::ESTADO_CERRADA ||
                                $model->estado == Sds_800_llamada::ESTADO_DERIVADA ||
                                $model->estado == Sds_800_llamada::ESTADO_ATENDIDA
                            ) {
                                echo  "block";
                            } else {
                                echo "none";
                            }
                            echo '">                        
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_salud" href="#aspsalud">
                                                    Aspectos de Salud
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="aspsalud" class="accordion-body collapse in">
                                                <div class="panel-body" id="atencion_content">                                    
                                                        
                                                    <div class="row">
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "sentimiento")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::SENTIMIENTO_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::SENTIMIENTO_BIEN =>
                                        "Bien",
                                        Sds_800_atencion::SENTIMIENTO_MAL =>
                                        "Mal",
                                        Sds_800_atencion::SENTIMIENTO_ELECCION =>
                                        "Es una eleccion de vida",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "situacion_salud")
                                ->widget(
                                    Select2::class,
                                    [
                                        "data" => $selectSituacionSalud,
                                        "options" => [
                                            "placeholder" => "Seleccionar opción ...",
                                            "id" => "situacion_salud",
                                            "disabled" => true
                                        ],
                                        "pluginOptions" => [
                                            "allowClear" => true
                                        ]
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "consumo_problematico")
                                ->widget(
                                    Select2::class,
                                    [
                                        "data" => $selectConsumoProblematico,
                                        "options" => [
                                            "placeholder" => "Seleccionar opción ...",
                                            "id" => "consumo_problematico",
                                            "disabled" => true
                                        ],
                                        "pluginOptions" => [
                                            "allowClear" => true
                                        ]
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "capacidad_limitada")
                                ->widget(
                                    Select2::class,
                                    [
                                        "data" => $selectCapacidadLimitada,
                                        "options" => [
                                            "placeholder" => "Seleccionar opción ...",
                                            "id" => "capacidad_limitada",
                                            "disabled" => true
                                        ],
                                        "pluginOptions" => [
                                            "allowClear" => true
                                        ]
                                    ]
                                ) . '
                                                        </div>
                                                        
                                                        
                                                    
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "orientado")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                )
                                ->label("¿Se encuentra orientado?")
                                . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "intoxicado")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "violentado")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "expresar")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                        
                                                    </div>
                
                                                    <div class="row">
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "beneficio")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "Seleccione una opción",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-2">' .
                                $form
                                ->field($obj_atencion, "tratamiento")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "Seleccione una opción",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-4">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "tratamiento_institucion"
                                )
                                ->textInput([
                                    "maxlength" => true,
                                    "disabled" => true

                                ]) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "tratamiento_profesional"
                                )
                                ->textInput([
                                    "maxlength" => true,
                                    "disabled" => true

                                ]) . '
                                                        </div>
                                                    </div>
                                                    <div class="row">  
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "alucinaciones")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';




                            echo Collapse::widget([]);
                            echo '
                                    <div class="panel-group" id="accordion_aspeconom"  style="display:';
                            if (
                                $model->estado == Sds_800_llamada::ESTADO_CERRADA ||
                                $model->estado == Sds_800_llamada::ESTADO_DERIVADA ||
                                $model->estado == Sds_800_llamada::ESTADO_ATENDIDA
                            ) {
                                echo  "block";
                            } else {
                                echo "none";
                            }
                            echo '">
                                    
                                    
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_aspeconom" href="#aspeconom">
                                                    Aspectos Económicos
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="aspeconom" class="accordion-body collapse in">
                                                <div class="panel-body" id="atencion_content">                                    
                                                        
                                                <div class="row">                               
                                                        <div class="col-md-2">' .
                                $form
                                ->field($obj_atencion, "nivel_estudio")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::ESTUDIO_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::ESTUDIO_PRIMARIO_INCOMPLETO =>
                                        "Primario Incompleto",
                                        Sds_800_atencion::ESTUDIO_PRIMARIO_COMPLETO =>
                                        "Primario Completo",
                                        Sds_800_atencion::ESTUDIO_SECUNDARIO_INCOMPLETO =>
                                        "Secundario Incompleto",
                                        Sds_800_atencion::ESTUDIO_SECUNDARIO_COMPLETO =>
                                        "Secundario Completo",
                                        Sds_800_atencion::ESTUDIO_TERCIARIO_OTRO_INCOMPLETO =>
                                        "Terciario/Otro Incompleto",
                                        Sds_800_atencion::ESTUDIO_TERCIARIO_OTRO_COMPLETO =>
                                        "Terciario/Otro Completo",
                                    ],
                                    [
                                        "prompt" =>
                                        "Seleccione una opción",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>                                    
                                                        <div class="col-md-2">' .
                                $form
                                ->field($obj_atencion, "sabe_leer")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                        "Sin Datos",
                                        Sds_800_atencion::RESPUESTA_SI =>
                                        "Si",
                                        Sds_800_atencion::RESPUESTA_NO =>
                                        "No",
                                    ],
                                    [
                                        "prompt" =>
                                        "Seleccione una opción",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                                                  
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "trabajo")
                                ->dropDownList(
                                    [
                                        Sds_800_atencion::TRABAJO_NO =>
                                        "No",
                                        Sds_800_atencion::TRABAJO_FORMAL =>
                                        "Formal",
                                        Sds_800_atencion::TRABAJO_INFORMAL =>
                                        "Informal",
                                    ],
                                    [
                                        "prompt" =>
                                        "-- Seleccione una opción --",
                                        "id" => "cmb_trabajo",
                                        "disabled" => true,
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-5">' .
                                $form
                                ->field(
                                    $obj_atencion,
                                    "trabajo_detalle"
                                )
                                ->textarea([
                                    "rows" => 2,
                                    "disabled" => true

                                ]) . '
                                                        </div>
                                                    
                                                </div>
                                                <div class="row">
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "r_situacion_laboral")
                                ->widget(
                                    Select2::class,
                                    [
                                        "data" => $selectSituacionLaboral,
                                        "options" => [
                                            "placeholder" => "Seleccionar opción ...",
                                            "id" => "r_situacion_laboral",
                                            "disabled" => true
                                        ],
                                        "pluginOptions" => [
                                            "allowClear" => true
                                        ]
                                    ]
                                ) . '
                                                        </div>
                                                        <div class="col-md-6">' .
                                $form
                                ->field($obj_atencion, "oficio")
                                ->textInput([
                                    "disabled" => true

                                ])
                                ->label(
                                    "Oficio"
                                ) . '
                                                        </div>
                                                        <div class="col-md-3">' .
                                $form
                                ->field($obj_atencion, "aportes_economicos")
                                ->widget(
                                    Select2::class,
                                    [
                                        "data" => $selectAportesEconomicos,
                                        "options" => [
                                            "placeholder" => "Seleccionar opción ...",
                                            "id" => "aportes_economicos",
                                            "disabled" => true
                                        ],
                                        "pluginOptions" => [
                                            "allowClear" => true
                                        ]
                                    ]
                                ) . '
                                                        </div>
                                                    </div>                                                                        
                
                                            </div>
                                        </div>
                                    </div>
                
                                      <br>';
                        }


                        ?>
















                        <?php echo Collapse::widget([]); ?>
                        <div class="panel-group" id="accordion_detalle">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle" href="#detalle">
                                            Informacion Complementaria
                                        </a>
                                    </h4>
                                </div>
                                <div id="detalle" class="accordion-body collapse in">
                                    <div class="panel-body" id="detalle_content">


                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">Observaciones</label>
                                                <div class="alert alert-detalle" role="alert">
                                                    <p><?php echo $obj_atencion->observaciones;  ?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class='col-md-12'>
                                                <label for="" style="display:<?= $model_atencion->archivo_salud ? "block" : "none;" ?>">Archivo de Salud</label>
                                                <div class="row text-center">
                                                    <div class="col-md-6" style="display:<?= $model_atencion->archivo_salud ? "block" : "none; text-align: center" ?>">
                                                        <!-- Valida si es pdf -->
                                                        <?php if (stripos($model_atencion->archivo_salud, 'application/pdf;base64,') != false) : ?>
                                                            <div class="row">
                                                                <object width="90%" height="500px" type="application/pdf" data="<?php echo $model_atencion->archivo_salud; ?>">
                                                                    <p>Archivo Adjunto no disponible.</p>
                                                                </object>
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="row" style="max-height:500px">
                                                                <div class='col-md-12' align="center" ;> <br>
                                                                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:70%;' id='base64image' src='<?php echo $model_atencion->archivo_salud; ?>' />
                                                                    <br>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="row" style="margin-top:2%; text-align: center;">
                                                            <?= Html::a("Ampliar", $model_atencion->archivo_salud, ['target' => '_blank', 'data-pjax' => "0", 'class' => 'btn btn-success', 'style' => 'width:80%']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <?= $form->field($model_atencion, 'idusuario')->hiddenInput()->label(false) ?>

                        <?= $form->field($model_atencion, 'idllamada')->hiddenInput()->label(false) ?>

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
                                <?php echo date_format(date_create($model->fecha_hora), 'd/m/Y H:i') ?></h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <h5><b>Atendió: </b>
                                <?php echo $model->usuario->nombre . ' ' . $model->usuario->apellido ?></h5>
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
                                            <?= $form->field($model, 'dni')->textInput(["id" => "txtDNI", "disabled" => true]) ?>
                                        </div>
                                        <div class="col-md-6" style="padding-top:30px;" id="txt_mensaje">

                                        </div>
                                        <!-- <div class="col-md-3" style="text-align: right;">
                                            <img id="renaper_foto" src="" alt="" height="75px" />
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'nombre')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'apellido')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php
                                            if ($model->fecha_nacimiento != null) {
                                                $model->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                                            }
                                            echo $form->field($model, 'fecha_nacimiento')->widget(DatePicker::class, [
                                                'name' => 'check_issue_date',
                                                'language' => 'es',
                                                'readonly' => false,
                                                'layout' => '{picker}{input}{remove}',
                                                'options' => [
                                                    'id' => 'fecha_nacimiento',
                                                    'class' => 'form-control input-md',
                                                    'disabled' => true
                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
                                                    'endDate' => date('d/m/Y'),
                                                    'todayHighlight' => true,
                                                    'autoclose' => true,
                                                ]
                                            ])->label('Fecha de Nacimiento'); ?>
                                            <small id='edad'><?php echo $model->edad ? ($model->edad == 1 ? "Edad {$model->edad} año " : "Edad {$model->edad} años ") : "" ?></small>
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
                                            <?= $form->field($model, 'telefono')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'domicilio')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'provincia')->textInput(['maxlength' => true, "disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'localidad')->textInput(['maxlength' => true, "disabled" => "true"]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'institucion')->textInput(['maxlength' => true, "disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'vinculo')->textInput(['maxlength' => true, "disabled" => "true"]) ?>
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
                                            <?= $form->field($model, 'afectado_dni')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'afectado_nombre')->textInput(['maxlength' => true, "disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form->field($model, 'afectado_apodo')->textInput(['maxlength' => true, "disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form->field($model, 'afectado_tratamiento')->textInput(['maxlength' => true, "disabled" => "true"]) ?>
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
                                                    <?= $form->field($model, 'latitud')->textInput(["disabled" => "true"]) ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?= $form->field($model, 'longitud')->textInput(["disabled" => "true"]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'direccion')->textInput(['id' => 'txtDireccion', 'maxlength' => true, 'readonly' => true]) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            echo CoordinatesPicker::widget([
                                                'model' => $model,
                                                'attribute' => 'coordenadas',
                                                'key' => 'AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg', // require , Put your google map api key
                                                'valueTemplate' => '{latitude},{longitude}', // Optional , this is default result format
                                                'options' => [
                                                    'style' => 'width: 100%; height: 405px', // map canvas width and height
                                                ],
                                                'enableSearchBox' => false, // Optional , default is true
                                                'searchBoxOptions' => [ // searchBox html attributes
                                                    'style' => 'width: 300px;display:none;', // Optional , default width and height defined in css coordinates-picker.css
                                                ],
                                                'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'), // optional , default is TOP_LEFT
                                                'mapOptions' => [
                                                    // google map options
                                                    // visit https://developers.google.com/maps/documentation/javascript/controls for other options
                                                    'mapTypeControl' => false, // Enable Map Type Control
                                                    'mapTypeControlOptions' => [
                                                        'style' => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
                                                        'position' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
                                                    ],
                                                    'streetViewControl' => true, // Enable Street View Control
                                                ],
                                                'clientOptions' => [
                                                    // jquery-location-picker options
                                                    'location' => [
                                                        'latitude' => $model->latitud,
                                                        'longitude' => $model->longitud,
                                                    ],
                                                    'radius' => 0,
                                                    'markerDraggable' => 0,
                                                    'addressFormat' => 'street_number', 'inputBinding' => [
                                                        'locationNameInput' => new JsExpression("$('#txtDireccion')"),
                                                    ]
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_detalle_derivacion" style="display:<?= $model->estado == Sds_800_llamada::ESTADO_DERIVADA ? "block" : "none" ?>">
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
                                            <?= $form->field($model, 'idderivacion')->dropdownList(
                                                $selectDerivacion,
                                                ['prompt' => 'Seleccionar derivación ...', 'id' => 'idderivacion', 'disabled' => true]
                                            )->label('Derivación');
                                            ?>
                                        </div>
                                        <div class="col-md-5" id="derivacion_data" style="padding-top:20px;">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <?= $form->field($model, 'derivacion_referente')->textInput(['disabled' => true]) ?>
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
                    <div class="panel-group" id="accordion_detalle_cierre" style="display:<?= $model->estado == Sds_800_llamada::ESTADO_CERRADA || $model->estado == Sds_800_llamada::ESTADO_DESPEJADA ? "block" : "none" ?>">
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
<?php
$this->registerJs(
    "$(document).ready(function() {                
        datos_derivacion();
    });
    "
);
?>
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