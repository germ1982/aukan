<?php

use app\models\Sds_800_llamada;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use pigolab\locationpicker\CoordinatesPicker;
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
    <h2>Detalle Ingreso de Violencia N°: <?php echo $model->idllamada; ?></h2>

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
                                    <div class="row">
                                        <div class="col-md-6 text-left">
                                            <?php echo $model->usuarioDeriva ? '<h5><b>Derivado por: </b>' . $model->usuarioDeriva->nombre . ' ' . $model->usuarioDeriva->apellido : '' ?></h5>
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