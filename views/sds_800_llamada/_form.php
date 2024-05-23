<?php

use app\models\Sds_800_llamada;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use app\models\Sds_800_atencion;




function write_to_console($data)
{
    $console = $data;
    if (is_array($console))
        $console = implode(',', $console);

    echo "<script>console.log('Console: " . $console . "' );</script>";
}

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_llamada */
/* @var $form yii\widgets\ActiveForm */
$this_model = Sds_800_llamada::findOne($model->idllamada);

$obj_atencion = Sds_800_atencion::find()
    ->where(['idllamada' => $model->idllamada])
    ->one();


switch ($model->area) {
    case Sds_800_llamada::AREA_SITUACIONDECALLE:
        $area = 'Situación de Calle';
        break;
    case Sds_800_llamada::AREA_FAMILIA:
        $area = 'Familia';
        break;
    case Sds_800_llamada::AREA_ADULTOSMAYORES:
        $area = 'Adultos Mayores';
        break;
    case Sds_800_llamada::AREA_INTERIOR:
        $area = 'Interior';
        break;
    case Sds_800_llamada::AREA_VIOLENCIA:
        $area = 'Violencia';
        break;
}
$this->title =
    ($model->isNewRecord ? 'Nuevo ' : 'Editar ') .
    'Ingreso de Situación ' .
    $area;
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
<div class="sds-800-llamada-form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <?php echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_llamante">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_llamante" href="#llamante">
                                        Datos del Llamante o Solicitante de Situación
                                    </a>
                                </h4>
                            </div>
                            <div id="llamante" class="accordion-body collapse in">
                                <div class="panel-body" id="llamante_content">
                                    <div style="display:<?= $model->area == 1 ? 'block;' : 'none;' ?>" class="row">
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'area_interviniente'
                                                )
                                                ->dropDownList(
                                                    Sds_800_llamada::ARRAY_AREAINTERVINIENTE,
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'dni')
                                                ->textInput([
                                                    'id' => 'txtDNI',
                                                    'disabled' =>
                                                    $model->estado !=
                                                        Sds_800_llamada::ESTADO_PENDIENTE,
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
                                                    'disabled' =>
                                                    $model->estado !=
                                                        Sds_800_llamada::ESTADO_PENDIENTE,
                                                    'class' =>
                                                    'btn btn-primary',
                                                    'title' => Yii::t(
                                                        'app',
                                                        'Consultar DNI Llamante'
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
                                        <div class="col-md-4" style="padding-top:35px">
                                            <?= $form
                                                ->field($model, 'solicitante')
                                                ->checkBox([
                                                    'id' => 'check_solicitante',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3" style="padding-top:30px;" id="txt_mensaje">
                                        </div>
                                    </div>
                                    <small id='idrisneu'></small>
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
                                                ],
                                                /* 'pluginEvents' => [
                                                    "changeDate" => "function(e) { calcular_edad('1989-02-02')}",
                                                    "changeYear" => "function(e) { calcular_edad('1989-02-02')}",
                                                    "changeMonth" => "function(e) { calcular_edad('1989-02-02')}",
                                                ],*/
                                            ])->label('Fecha de Nacimiento'); ?>
                                            <small id='edad'><?php echo $model->edad ? ($model->edad == 1 ? "Edad {$model->edad} año " : "Edad {$model->edad} años ") : "" ?></small>
                                        </div>
                                    </div>
                                    <div class="row" style="display: <?= $model->estado !=
                                                                            Sds_800_llamada::ESTADO_PENDIENTE
                                                                            ? 'none'
                                                                            : 'block' ?>;">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'nacionalidad')
                                                ->dropdownList(
                                                    $selectNacionalidad,
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar Nacionalidad ...',
                                                        'disabled' => 'true',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'sexo')
                                                ->dropdownList(
                                                    $selectGenero,
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar Genero ...',
                                                        'disabled' => 'true',
                                                    ]
                                                ) ?>
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
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'domicilio')->textInput(["disabled" => "true"]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if ($model->estado != Sds_800_llamada::ESTADO_PENDIENTE) { ?>
                                                <?= $form->field($model, 'provincia')->dropDownList($listProvincias, ["disabled" => "true"]) ?>

                                            <?php } else { ?>
                                            <?= $form->field($model, 'provincia')->widget(Select2::class, [
                                                    'data' => $listProvincias,
                                                    'options' => [
                                                        'placeholder' => 'Seleccionar Provincia ...',
                                                        'id' => 'cmb_provincia',
                                                        'onchange' => 'cargarLocalidades();',
                                                        'disabled' => true
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ])->label('Provincia');
                                            } ?>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="hidden" id="idLocalidadSelected" name="idLocalidadSelected">
                                            <?php if ($model->estado != Sds_800_llamada::ESTADO_PENDIENTE) { ?>
                                                <label class="form-label">Localidad</label>
                                                <input type="text" class="form-control" value="<?php echo $model->localidad0->descripcion ?>" readonly>
                                            <?php } else { ?>
                                            <?= $form->field($model, 'localidad')->widget(Select2::class, [
                                                    'options' => [
                                                        'placeholder' => 'Seleccionar Localidad ...',
                                                        'id' => 'localidad',
                                                        'disabled' => true
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ]
                                                ]);
                                            } ?>
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

                                        <div class=<?= ($model->area == Sds_800_llamada::AREA_FAMILIA) ? "col-md-3" : "col-md-6" ?>>
                                            <?= $form
                                                ->field($model, 'vinculo')
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' => 'true',
                                                ]) ?>
                                        </div>
                                        <?php
                                        if ($model->area == Sds_800_llamada::AREA_FAMILIA) { ?>
                                            <div class="col-md-3">
                                                <?=
                                                $form->field($model, 'profesional_interviniente')->dropdownList(
                                                    $listprofesionales,
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccionar Profesional ...',
                                                            'options' => [
                                                                'disabled' => true,
                                                                'selected' => true
                                                            ]
                                                        ],
                                                        'id' => 'profesional_interviniente',
                                                        'disabled' => true
                                                    ]
                                                );
                                                ?>
                                            </div>
                                        <?php } ?>
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
                                        Detalle de la Situación
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle" class="accordion-body collapse in">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-12" id="detalle_texto_container">
                                            <?= $form->field($model, 'detalle')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'detalle_texto',
                                                ],
                                            ])->label("Descripción") ?>
                                        </div>
                                    </div>
                                    <div class="row" id="div_afectado">
                                        <div class="col-md-5">
                                            <?= $form
                                                ->field($model, 'afectado_dni')
                                                ->textInput([
                                                    'disabled' => 'true',
                                                    'hide' =>
                                                    $model->solicitante ==
                                                        1,
                                                ]) ?>
                                        </div>
                                        <div class="col-md-1" style="padding-top:25px;">
                                            <?= Html::a(
                                                '<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">',
                                                null,
                                                [
                                                    'name' => 'btn_pui_af',
                                                    'id' => 'btn_pui_af',
                                                    'data-request-method' =>
                                                    'post',
                                                    'data-toggle' => 'tooltip',
                                                    'style' =>
                                                    'padding:0px;padding-left:2px;',
                                                    'class' => 'btn',
                                                    'title' => Yii::t(
                                                        'app',
                                                        'Consulta a Portal Unificado'
                                                    ),
                                                ]
                                            ) ?>
                                        </div>
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'afectado_tratamiento')->dropDownList(
                                                [
                                                    Sds_800_llamada::PACIENTE_ADICCIONES => "Paciente Adicciones",
                                                    Sds_800_llamada::PACIENTE_MENTAL => "Paciente Mental",
                                                    Sds_800_llamada::PACIENTE_DUALES => "Paciente Dual"
                                                ],
                                                [
                                                    'prompt' => '-- Seleccione una opción --',
                                                    'disabled' => 'true'
                                                ]
                                            ) ?>
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
                                            <?= $form
                                                ->field($model, 'tipo')
                                                ->dropdownList(
                                                    $selectSituacionTipo,
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar tipo ...',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-4 required">
                                            <?= $form->field($model, 'idderivacion')->dropdownList(
                                                $selectDerivacion,
                                                [
                                                    'prompt' => 'Seleccionar derivación ...',
                                                    'required' => $model->estado == Sds_800_llamada::ESTADO_DERIVADA ? true : false,
                                                    'id' => 'idderivacion'
                                                ]
                                            )->label('Derivación');
                                            ?>
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
                                                ->textInput() ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="derivacion_detalle_texto_container">
                                            <?= $form->field($model, 'derivacion_detalle')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'derivacion_detalle_texto',
                                                ],
                                            ])->label("Detalle derivación") ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_detalle_cierre" style="display:<?= $model->estado ==
                                                                                                Sds_800_llamada::ESTADO_CERRADA ||
                                                                                                $model->estado == Sds_800_llamada::ESTADO_DESPEJADA

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
                                        <div class="col-md-12" id="cierre_detalle_texto_container">
                                            <?= $form->field($model, 'cierre_detalle')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'cierre_detalle_texto',
                                                ],
                                            ])->label("") ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_detalle_direccion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle_direccion" href="#detalle_direccion">
                                        Detalle Dirección
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_direccion" class="accordion-body collapse in">
                                <div class="panel-body" id="detalle_direccion_content">
                                    <div class="row">
                                        <div class="col-md-6">
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
                                                            'id' =>
                                                            'txtDireccion',
                                                            'maxlength' => true,
                                                            'readonly' => true,
                                                        ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo $form
                                                ->field($model, 'coordenadas')
                                                ->widget(
                                                    '\pigolab\locationpicker\CoordinatesPicker',
                                                    [
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
                                                            'addressFormat' =>
                                                            'street_number',
                                                            'inputBinding' => [
                                                                'latitudeInput' => new JsExpression(
                                                                    "$('#txtLatitud')"
                                                                ),
                                                                'longitudeInput' => new JsExpression(
                                                                    "$('#txtLongitud')"
                                                                ),
                                                                'locationNameInput' => new JsExpression(
                                                                    "$('#txtDireccion')"
                                                                ),
                                                            ],
                                                        ],
                                                    ]
                                                )
                                                ->label(''); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Checkbox No Corresponde -->
                    <div class="col-md-12" style="text-align: right; display:<?= !$model->isNewRecord &&
                                                                                    $model->estado == Sds_800_llamada::ESTADO_PENDIENTE
                                                                                    ? 'block'
                                                                                    : 'none' ?>">
                        <?= $form
                            ->field($model, 'estado')
                            ->checkbox(['disabled' => 'true']) ?>
                    </div>
                    <?= $form->field($model, 'idpersona')->hiddenInput()->label(false) ?>
                    <?php if (!Yii::$app->request->isAjax) { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success', 'id' => 'btnGuardar']) ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php
$estado = $model->estado;
$nombreTiny = 'NOT_DEFINED';
$mensajeTiny = 'NOT_DEFINED';
switch ($estado) {
    case Sds_800_llamada::ESTADO_DERIVADA:
        $nombreTiny = 'derivacion_detalle_texto';
        $mensajeTiny = 'El \"Detalle de Derivación\" no puede estar vacío.';
        break;
    case Sds_800_llamada::ESTADO_DESPEJADA:
        $nombreTiny = 'cierre_detalle_texto';
        $mensajeTiny = 'El \"Detalle del Cierre - Despeje\" no puede estar vacío.';
        break;
    default:
        $nombreTiny = 'NOT_DEFINED';
        $mensajeTiny = 'NOT_DEFINED';
        break;
}
$isNewRecord = $model->isNewRecord ? $model->isNewRecord : 0;

$this->registerJs(
    "$(document).ready(function() {
        $('#detalle_texto_container .ql-editor').attr('contenteditable', false);

        datos_persona(true);
        datos_derivacion();
    });

    $('#txtDNI').change(function(){        
        datos_persona(false);
    });
    

    $('#btn_pui').click(function(){        
        var dni_campo = $('#txtDNI').val();        
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
    });

    $('#btn_pui_af').click(function(){        
        var dni_campo = $('#sds_800_llamada-afectado_dni').val();        
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
    });

    $('#btn_dni').click(function(){        
        datos_persona(false);
    });

    $('#idderivacion').change(function(){        
        datos_derivacion();
    });

    $('#btnGuardar').click(function(e){
        const isNewRecord = $isNewRecord;
        const nombreTiny = '$nombreTiny';
        const mensajeTiny = '$mensajeTiny';
        if (isNewRecord) {
            const detalle_texto =  $('#detalle_texto').val();
    
            const parser = new DOMParser();
            const { textContent } = parser.parseFromString(detalle_texto, 'text/html').documentElement;
            detalleTextoSinHTML = textContent.trim();
    
            if (!detalle_texto || detalle_texto.length < 1 || !detalleTextoSinHTML){
                alert('La descripción del \"Detalle de la Situación\" no puede estar vacío.');
                e.preventDefault();
            }
        } else if (nombreTiny !== 'NOT_DEFINED') {
            const textoTiny =  $('#$nombreTiny').val();
    
            const parser = new DOMParser();
            const { textContent } = parser.parseFromString(textoTiny, 'text/html').documentElement;
            cierreDetalleTextoSinHTML = textContent.trim();
    
            if (!textoTiny || textoTiny.length < 1 || !cierreDetalleTextoSinHTML){
                alert(mensajeTiny);
                e.preventDefault();
            }
        }
    });
    "
); ?>

<script>
    var dni = <?php echo isset($model->dni) ? $model->dni : 0; ?>;
    var estado = <?php echo $model->estado; ?>;

    function datos_persona(primera_vez = false) {
        var dni_campo = $('#txtDNI').val();
        if (dni != dni_campo || primera_vez) {
            if (dni_campo != '') {
                $('#txt_mensaje').html("Buscando datos de Persona...");
                dni = dni_campo;
                $.post("index.php?r=sds_800_llamada/validar_dni&dni=" + dni, function(data) {
                    data = $.parseJSON(data);
                    if (data.length === 0) {
                        datos_renaper(dni);
                    } else {
                        $("#sds_800_llamada-idpersona").val(data[0]['idpersona']);
                        $("#sds_800_llamada-nombre").val(data[0]['nombre']);
                        $("#sds_800_llamada-apellido").val(data[0]['apellido']);
                        $("#fecha_nacimiento").val(formatearFecha(data[0]['fecha_nacimiento']));
                        calcular_edad((data[0]['fecha_nacimiento']));
                        $("#sds_800_llamada-nacionalidad").val(data[0]['nacionalidad']);
                        $("#sds_800_llamada-sexo").val(data[0]['genero']);
                        $("#cmb_provincia").val(data['idprovincia']).trigger("change");
                        $("#idLocalidadSelected").val(data['idlocalidad']);
                        if (Object.keys(data).length > 1 && data[1]) {
                            $("#sds_800_llamada-telefono").val(data[1]['telefono'] ? data[1]['telefono'] : '');
                            $("#sds_800_llamada-domicilio").val(data[1]['domicilio'] ? data[1]['domicilio'] : '');
                            $("#sds_800_llamada-localidad").val(data[1]['idlocalidad'] ? data[1]['domicilio'] : '');
                        } else {
                            $("#sds_800_llamada-telefono").val("");
                            $("#sds_800_llamada-domicilio").val("");
                            $("#sds_800_llamada-localidad").val("");
                        }
                        $('#txt_mensaje').html("");
                        if (data.idrisneu) {
                            $('#idrisneu').html(`Persona con RISNeu N°` + data.idrisneu);
                        } else {
                            $('#idrisneu').html(``);
                        }
                        habilitar_controles();
                    }
                });
            }
        }
    }

    function datos_renaper(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            if (data.status == "error") {
                $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos();
            } else {
                var nombre = "";
                var apellido = "";
                var domicilio = "";
                var localidad = "";
                var foto = "";
                var fecha_nacimiento = null;
                $.each(data, function(ind, elem) {
                    //console.log(ind);
                    if (ind == 'records') {
                        //console.log(elem[0]);
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
                        domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                        localidad = elem[0].result.ciudad;
                        //foto = elem[0].result.foto;
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                    }
                });
                if (fecha_nacimiento != null) {
                    $("#sds_800_llamada-idpersona").val(0);
                    $("#sds_800_llamada-nombre").val(corregir_palabra(nombre));
                    $("#sds_800_llamada-apellido").val(corregir_palabra(apellido));
                    $("#fecha_nacimiento").val(fecha_nacimiento);
                    $("#sds_800_llamada-nacionalidad").val('');
                    $("#sds_800_llamada-sexo").val('');
                    $("#sds_800_llamada-domicilio").val(domicilio);
                    //$("#sds_800_llamada-localidad").val(getIdLocalidad(corregir_palabra(localidad))).trigger("change");
                    $("#sds_800_llamada-localidad").val('');
                    /* $("#renaper_foto").attr("src", foto); */
                    $('#txt_mensaje').html("");
                    habilitar_controles();
                }
            }
        });
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#sds_800_llamada-nombre").val('');
        $("#sds_800_llamada-apellido").val('');
        $("#fecha_nacimiento").val('');
        $("#sds_800_llamada-nacionalidad").val('');
        $("#sds_800_llamada-sexo").val('');
        $("#sds_800_llamada-telefono").val("");
        $("#sds_800_llamada-domicilio").val("");
        $("#sds_800_llamada-localidad").val("");
        $("#sds_800_llamada-idpersona").val('0');
    }

    function getIdLocalidad(localidad) {
        $.post("index.php?r=sds_com_localidad/get_id_localidad&localidad=" + localidad, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                return "";
            } else {
                $("#sds_800_llamada-localidad").val(data['idlocalidad']);
            }
        });
    }

    function habilitar_controles() {
        $("#sds_800_llamada-nombre").prop("disabled", false);
        $("#sds_800_llamada-apellido").prop("disabled", false);
        $("#fecha_nacimiento").prop("disabled", false);
        $("#sds_800_llamada-telefono").prop("disabled", false);
        $("#sds_800_llamada-domicilio").prop("disabled", false);
        $("#sds_800_llamada-nacionalidad").prop("disabled", false);
        $("#sds_800_llamada-sexo").prop("disabled", false);
        $("#cmb_provincia").prop("disabled", false);
        $("#localidad").prop("disabled", false);
        $("#sds_800_llamada-vinculo").prop("disabled", false);
        $("#sds_800_llamada-institucion").prop("disabled", false);
        $("#profesional_interviniente").prop("disabled", false);
        // $("#sds_800_llamada-detalle_texto").prop("disabled", false);
        $("#detalle_texto_container .ql-editor").attr('contenteditable', true);
        $("#sds_800_llamada-afectado_dni").prop("disabled", false);
        $("#sds_800_llamada-afectado_nombre").prop("disabled", false);
        $("#sds_800_llamada-afectado_apodo").prop("disabled", false);
        $("#sds_800_llamada-afectado_tratamiento").prop("disabled", false);
        if (estado != 0) {
            $("#sds_800_llamada-nombre").prop("readOnly", true);
            $("#sds_800_llamada-apellido").prop("readOnly", true);
            $("#fecha_nacimiento").prop("readOnly", true);
            $("#sds_800_llamada-telefono").prop("readOnly", true);
            $("#sds_800_llamada-domicilio").prop("readOnly", true);
            $("#sds_800_llamada-institucion").prop("readOnly", true);
            $("#sds_800_llamada-vinculo").prop("readOnly", true);
            $("#profesional_interviniente").prop("disabled", true);
            // $("#sds_800_llamada-detalle").prop("readOnly", true);
            // $("#sds_800_llamada-detalle_texto").prop("disabled", true);
            $("#detalle_texto_container .ql-editor").attr('contenteditable', false);
            $("#sds_800_llamada-afectado_dni").prop("readOnly", true);
            $("#sds_800_llamada-afectado_nombre").prop("readOnly", true);
            $("#sds_800_llamada-afectado_apodo").prop("readOnly", true);
            $("#sds_800_llamada-afectado_tratamiento").prop("disabled", true);
        } else {
            $("#sds_800_llamada-estado").prop("disabled", false);
        }
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
        palabra = palabra.replace("�", " ");
        return palabra;
    }

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

    function ocultar_afectados() {
        if ($('#check_solicitante').prop('checked')) {
            $('#div_afectado').hide();
        } else {
            $('#div_afectado').show();
        }
    }


    function calcular_edad(fecha_nacimiento) {
        var hoy = new Date();
        var cumpleanos = new Date(fecha_nacimiento);
        var edad = hoy.getFullYear() - cumpleanos.getFullYear();
        var m = hoy.getMonth() - cumpleanos.getMonth();

        if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
            edad--;
        }
        $("#edad").val(edad);
        if (edad != 1) {
            $('#edad').text('Edad actual ' + edad + ' años.');
        } else {
            $('#edad').text('Edad actual ' + edad + ' año.');
        }
    }

    function cargarLocalidades() {
        if ($("#cmb_provincia").val()) {
            $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {
                $("select#localidad").html(data);
                $("#localidad").val($("#idLocalidadSelected").val()).trigger("change");
                $("#localidad").prop("disabled", false);
            });
        } else {
            $("#localidad").val(null).trigger("change");
            $("#localidad").prop("disabled", true);
        }
    }
</script>