<?php

use app\controllers\Sds_com_localidadController;
use app\controllers\Sds_com_personaController;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use kartik\select2\Select2;
use pigolab\locationpicker\CoordinatesPicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = 'Georeferencia';
$this->params['breadcrumbs'][] = $this->title;
$pais = Sds_com_configuracion::findOne($model->nacionalidad);
$genero = Sds_com_configuracion::findOne($model->genero);
$f_nacimiento = ($model->fecha_nacimiento != null ? date('d/m/Y', strtotime($model->fecha_nacimiento)) : null);


if ($model->latitud == null) {
    $model->latitud = -38.95167840000001;
}
if ($model->longitud == null) {
    $model->longitud = -68.05918880000002;
}

$texto_renaper = "<i>(No pudieron obtenerse datos desde RENAPER)</i>";
?>
<style>
    .field-sds_com_persona-georeferencia {
        margin-top: 0;
        padding-top: 0;
    }
</style>
<?php if (Yii::$app->session->hasFlash('error')) { ?>
    <div class="alert alert-danger alert-dismissable" id="alert-fail-save">
        <h4><i class="icon fas fa-times"></i> ¡UPS! Algo falló.</h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php } ?>
<style>
    .btn-map:hover {
        border: 1px solid #3c763d !important;
        background-color: #D4D4DF !important;
        transition: 0.7s;
    }

    .filas {
        border-bottom: 1px solid #5DADE2;
        padding: 13px 0;
    }

    .centrar {
        padding-top: 5px;
        font-size: 15px;
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
                <span><a href="index.php?r=sds_com_persona&get_query=1">Personas</a></span>
            </li>
            <li>
                <span><u><?= ($model->apellido ? $model->apellido . ', ' : '')  . ($model->nombre ? $model->nombre : '') ?></u></a></span>
            </li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="sds-com-persona-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <section class="panel-featured panel-featured-primary">
                <header class="panel-heading bg-default">
                    <h2 class="panel-title">
                        Datos persona
                    </h2>
                </header>
                <div class="panel-body" style="height: 430px;">
                    <div class="row" style="padding:0 30px 0 30px">
                        <div class="row" style="border-bottom:1px solid #5DADE2;padding-bottom:10px;">
                            <?php
                            $nya_sds = strtoupper(($model->apellido ? $model->apellido . ', ' : '') . ($model->nombre ? $model->nombre : ''));
                            $nya_renaper = strtoupper($renaper != null ? "$renaper->apellido, $renaper->nombres" : $texto_renaper);
                            ?>
                            <?= "<b>" . ($model->apellido ? $model->apellido . ', ' : '') . ($model->nombre ? $model->nombre : '') . "</b> " .
                                ($nya_sds != $nya_renaper && $renaper != null ? "(RENAPER: $renaper->apellido, $renaper->nombres)" : ($renaper != null ? " " : $texto_renaper)) ?>
                        </div>
                        <div class="row filas">
                            <b>DNI:</b>
                            <?= $model->documento ?>
                        </div>
                        <div class="row filas">
                            <b>Genero:</b>
                            <?= $genero->descripcion ?>
                        </div>
                        <div class="row filas">
                            <b>Nacionalidad:</b>
                            <?= $pais->descripcion ?>
                        </div>
                        <div class="row filas">
                            <!-- <?= $model->nombre . ' ' . $model->apellido . ' - ' .
                                        'DNI ' . $model->documento . ' - ' .
                                        (($pais != null) ? $pais->descripcion : 'SIN DATOS') . ' - ' .
                                        (($genero != null) ? $genero->descripcion : 'SIN DATOS')
                                    ?> -->
                            <b>Fecha Nacimiento:</b>
                            <?= $f_nacimiento ?>
                        </div>
                        <div class="row filas">
                            <b>Calle Renaper:</b> <?= $renaper != null ? $renaper->calle . ' ' . $renaper->numero : $texto_renaper ?>
                        </div>
                        <div class="row filas">
                            <b> Localidad Renaper: </b> <?= $renaper != null ? $renaper->ciudad . ' | ' . $renaper->provincia : $texto_renaper ?>
                        </div>
                        <div class="row" style="padding: 13px 0;">
                            <b> Lugar Voto: </b> <?= $model->lugar_voto ?>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-xs-6" style="padding-left: 0px;">
                                <a class="btn btn-primary" href="javascript:history.back(1)">Volver </a>
                            </div>
                            <div class="col-md-6 col-xs-6" style="padding-right: 0px;">
                                <?php if (!Yii::$app->request->isAjax) { ?>
                                    <div class="form-group" style="text-align: right;">
                                        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
        <div class="col-md-8">
            <section class="panel-featured panel-featured-primary">
                <header class="panel-heading bg-default" style="padding: 11px 18px 1px 18px;">
                    <div class="row">
                        <div class="col-md-5" style="padding-left: 0px;">
                            <div class="col-md-3" style="padding-left: 7px; padding-top: 6px;">
                                <b>Domicilio</b>
                            </div>
                            <div class="col-md-9" style="padding-left: 0px;">
                                <?= $form->field($model, 'domicilio_calle')->textInput(['id' => 'txtDireccion', 'value' => (($model->domicilio_calle != null) ? $model->domicilio_calle : ($renaper != null ? $renaper->calle : ""))])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding: 0px;">
                            <div class="col-md-2" style="padding: 0px; padding-top: 6px;">
                                <b>N°</b>
                            </div>
                            <div class="col-md-6" style="padding: 0px; ">
                                <?= $form->field($model, 'domicilio_numero')->textInput(['id' => 'txtNumero', 'value' => (($model->domicilio_numero != null) ? $model->domicilio_numero : ($renaper != null ? $renaper->numero : ""))])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-md-4" style="padding: 0px;">
                            <div class="col-md-3" style="padding: 0px; padding-top: 6px;">
                                <b>Localidad: </b>
                            </div>
                            <div class="col-md-9" style="padding: 0px; padding-left: 2px;">
                                <?= $form->field($model, 'idlocalidad')->widget(Select2::class, [
                                    'id' => 'localidad',
                                    'data' => ArrayHelper::map(
                                        Sds_com_localidad::find()->all(),
                                        'idlocalidad',
                                        'descripcion',
                                    ),
                                    'options' => [
                                        'placeholder' => 'Seleccione Localidad...',
                                        'value' => $model->idlocalidad != null ? $model->idlocalidad : Sds_com_localidad::ID_NEUQUEN
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'disabled' => false,
                                    ]
                                ])->label(false);
                                $localidad = Sds_com_localidad::findOne($model->idlocalidad);
                                ?>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-map" id="btn_mapa" style="height: 34px; width: 41px;background:#FFFFFF;border: 1px solid #B8B8C6;">
                                <svg xmlns="http://www.w3.org/2000/svg" height="30" width="20" viewBox="0 0 1011.27 1673.55">
                                    <g fill="none">
                                        <path fill="#34A853" d="M214.3 826.7c34.5 43.1 69.5 97.1 87.9 129.8 22.4 42.5 31.6 71.2 48.3 122.4 9.8 28.2 19 36.8 38.5 36.8 21.3 0 31-14.4 38.5-36.8 15.5-48.3 27.6-85 46.5-120.1 37.3-67.2 84.5-127 130.4-184.4 12.6-16.1 93.1-110.9 129.3-186.1 0 0 44.2-82.2 44.2-197.1 0-107.4-43.7-182.1-43.7-182.1L607.8 243l-77 202.2-19 27.6-4 5.2-5.2 6.3-8.6 10.3-12.6 12.6-68.4 55.7-170.6 98.8z" />
                                        <path fill="#FBBC04" d="M37.9 574.5c41.9 95.4 121.8 178.7 176.4 252.2l289-342.4s-40.8 53.4-114.3 53.4c-82.2 0-148.8-65.5-148.8-148.2 0-56.9 33.9-95.9 33.9-95.9l-196 52.3z" />
                                        <path fill="#4285F4" d="M506.7 17.8c95.9 31 178.1 95.9 227.5 191.9l-231 275.2s33.9-39.6 33.9-95.9c0-84.5-71.2-148.2-148.2-148.2-73 0-114.9 52.9-114.9 52.9V120.1z" />
                                        <path fill="#1A73E8" d="M90.7 139c57.4-68.4 158-139 297-139C454.9 0 506 17.8 506 17.8L274 293.6H109.7z" />
                                        <path fill="#EA4335" d="M37.9 574.5S0 499.2 0 390.7c0-102.8 40.2-192.5 91.3-251.1l183.3 154.5z" />
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="panel-body" style="margin-top: -22px !important;padding:0px !important;height: 450px">
                    <?php
                    echo $form->field($model, 'georeferencia')->widget('\pigolab\locationpicker\CoordinatesPicker', [
                        'id' => 'map',
                        //'key' => 'AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg', // require , Put your google map api key
                        'key' => 'AIzaSyD2lQ1q4riArDDz3xniyW8jMlP8oRoTCZ4', // require , Put your google map api key
                        'valueTemplate' => '{latitude},{longitude}', // Optional , this is default result format
                        'options' => [
                            'style' => 'width: 100%; height: 385px;margin:0;border:1px solid #5DADE2;', // map canvas width and height
                        ],
                        'enableSearchBox' => false, // Optional , default is true
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
                            'radius' => false,
                            'addressFormat' => 'street_number',
                            'inputBinding' => [
                                'locationNameInput' => new JsExpression("$('#calle')"),
                            ]
                        ]
                    ])->label('');
                    ?>
                </div>
            </section>
        </div>
        <?php ActiveForm::end(); ?>
        <?= $form->field($model, 'latitud')->hiddenInput(['id' => 'latitud', 'value' => (($model->latitud != null) ? $model->latitud : null)])->label(false) ?>
        <?= $form->field($model, 'longitud')->hiddenInput(['id' => 'longitud', 'value' => (($model->longitud != null) ? $model->longitud : null)])->label(false) ?>
    </div>
    <?php
    $cp = 8300;
    if (isset($renaper->codigo_postal)) {
        if ($renaper->codigo_postal != ' ' && $renaper->codigo_postal != '') { //En ocasiones RENAPER responde con espacios y en ocasiones vacios
            $cp = intval($renaper->codigo_postal);
        }
    }
    $script = <<<JS
$.post("index.php?r=sds_com_localidad/get_id_localidad_por_codigo_postal&codigo_postal="+$cp, function(data){
    $("#sds_com_persona-idlocalidad").val(parseInt(data));
    //Obtengo y reinicio el select2 de localidad
    settings = $("#sds_com_persona-idlocalidad").attr('data-krajee-select2');
    settings = window[settings];
    $("#sds_com_persona-idlocalidad").select2(settings);
});

$("#latitud").change(function() {
    $("#latitud").val(lat);
});

$("#longitud").change(function() {
    $("#longitud").val(lng);
});

if($("#latitud").val()!=null && $("#longitud").val()!=null){
    var latitud=$("#latitud").val();
    var longitud=$("#longitud").val();
    refrescarMapa(latitud, longitud);
}
var map = null;
var marker = null;

$("#btn_mapa").click(function(){
    refrescarMapa(null, null, true);
    //get_for_address();
});

    function geocodeAddress(geocoder, resultsMap, click=false) {
        var calle = $('#txtDireccion').val();
        var numero = $('#txtNumero').val();
        var localidad = $("#sds_com_persona-idlocalidad :selected").text();
        if (localidad==null){
            localidad= 'Neuquén Capital';
        }
        var address = calle+' '+numero+', '+localidad+', Argentina';
        geocoder.geocode({'address': address}, function (results, status) {
            if (status === 'OK') {
                var posicion = results[0].geometry.location;
                if(!click){
                    if($("#latitud").val()!=null && $("#longitud").val()!=null){
                        posicion = {
                            lat: parseFloat($("#latitud").val()),
                            lng: parseFloat($("#longitud").val())
                        }
                    }
                }
                resultsMap.setCenter(posicion);
                marker = new google.maps.Marker({
                    draggable: true,
                    map: resultsMap,
                    position: posicion,
                    title: 'Algo', 
                    type: 'point'
                });
                $("#latitud").val(marker.position.lat());
                $("#longitud").val(marker.position.lng());
                
                marker.addListener('dragend', function () {
                    $("#latitud").val(marker.position.lat());
                    $("#longitud").val(marker.position.lng());
                });
            } else {
                alert("No se pudo geolocalizar la dirección");
            }
        });
    }

    function get_for_address(){

    }

    function refrescarMapa(latitud, longitud, click=false) {
        latitud=latitud!=null ? parseFloat(latitud):-38.9519568;
        longitud=longitud!=null ? parseFloat(longitud):-68.0613817;

        var geocoder = new google.maps.Geocoder();
        map = new google.maps.Map(document.getElementById('map-map'), {
            zoom: 17,
            center: { lat: latitud, lng: longitud }
        });
        geocodeAddress(geocoder, map, click);
    }
JS;
    $this->registerJs($script);
    ?>