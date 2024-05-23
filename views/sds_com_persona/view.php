<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

if ($model->latitud == null) {
    $model->latitud = -38.95167840000001;
}
if ($model->longitud == null) {
    $model->longitud = -68.05918880000002;
}

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_persona */

$this->title = 'Personas';
$this->params['breadcrumbs'][] = $this->title;
$genero = Sds_com_configuracion::findOne($model->genero);
$pais = Sds_com_configuracion::findOne($model->nacionalidad);
$f_nacimiento = ($model->fecha_nacimiento != null ? date('d/m/Y', strtotime($model->fecha_nacimiento)) : null);
$domicilio = $model->domicilio_calle . ' ' . $model->domicilio_numero;
$localidad = Sds_com_localidad::findOne($model->idlocalidad);
?>
<style>
    .input-color {
        background-color: #fff !important;
    }

    .filas {
        border-bottom: 1px solid #5DADE2;
        padding: 15px 0;
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
                <span><a href="index.php?r=sds_com_persona"><?= $this->title ?></a></span>
            </li>
            <li>
                <span><u><?= $model->apellido . ', ' . $model->nombre ?></u></a></span>
            </li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="sds-com-persona-view">
    <div class="row">
        <div class="col-md-3">
            <section class="panel-featured panel-featured-primary">
                <header class="panel-heading bg-default">
                    <h2 class="panel-title">
                        <?= $model->apellido . ', ' . $model->nombre ?>
                    </h2>
                </header>
                <div class="panel-body" style="height: 385px;">
                    <div class="row" style="padding:0 30px 0 30px">
                        <div class="row" style="border-bottom:1px solid #5DADE2;padding-bottom:10px;">
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
                            <b>Fecha Nacimiento:</b>
                            <?= $f_nacimiento ?>
                        </div>
                        <div class="row filas">
                            <b>Domicilio:</b>
                            <?= ($model->domicilio_calle == null && $model->domicilio_numero == null ? ' - SIN DATOS - ':$domicilio ) ?>
                        </div>
                        <div class="row filas">
                            <b>Localidad:</b>
                            <?= ($localidad != null ? $localidad->descripcion : ' - SIN DATOS - ') ?>
                        </div>
                        <div class="row" style="margin-top: 20px;text-align:center">
                            <?= Html::a('<span class="btn btn-primary" style="">Volver</span>', $current_url) ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-9">
            <section class="panel-featured panel-featured-primary">
                <header class="panel-heading bg-default">
                    <h2 class="panel-title">
                        Mapa
                        <i class="fas fa-map-marker-alt"></i>
                    </h2>
                </header>
                <div class="panel-body" style="margin-top:-37px !important;padding:0px !important;height: 420px">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'latitud')->hiddenInput(['id' => 'latitud', 'value' => (($model->latitud != null) ? $model->latitud : null), 'class' => 'form-control input-color',])->label(false) ?>
                    <?= $form->field($model, 'longitud')->hiddenInput(['id' => 'longitud', 'value' => (($model->longitud != null) ? $model->longitud : null), 'class' => 'form-control input-color',])->label(false) ?>
                    <?php
                    echo $form->field($model, 'georeferencia')->widget('\pigolab\locationpicker\CoordinatesPicker', [
                        'id' => 'map',
                        'key' => 'AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg', // require , Put your google map api key
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
                    ActiveForm::end();
                    ?>
                </div>
            </section>
        </div>
    </div>
</div>
<?php
$script = <<<JS

    // $("#latitud").change(function() {
    //     $("#latitud").val(lat);
    // });
    
    // $("#longitud").change(function() {
    //     $("#longitud").val(lng);
    // });
    
    if($("#latitud").val()!=null && $("#longitud").val()!=null){
        var latitud=$("#latitud").val();
        var longitud=$("#longitud").val();
        refrescarMapa(latitud, longitud);
    }
    var map = null;
    var marker = null;
    
    $(document).ready(function(){
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
                        if($("#latitud").val()!=null && $("#longitud").val()!=null){
                            posicion = {
                                lat: parseFloat($("#latitud").val()),
                                lng: parseFloat($("#longitud").val())
                            }
                        }
                    resultsMap.setCenter(posicion);
                    marker = new google.maps.Marker({
                        draggable: false,
                        map: resultsMap,
                        position: posicion,
                        title: 'Domicilio', 
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
</div>