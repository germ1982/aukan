<?php

use app\controllers\SiteController;
use app\models\Localidades;
use app\models\Provincias;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Edificio */

$id_nqn = Provincias::find()
    ->where(['like', 'provincia', 'Neuquén'])
    ->select('id')
    ->scalar();

$localidades = Localidades::get_localidades($id_nqn);

?>


<div class="edificio-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">

            <div class="row">
                <div class="col-md-10">
                    <?= $form->field($model, 'descripcion_fija')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-2" style="padding-top:30px;">
                    <?= $form->field($model, 'activo')->checkbox([
                        'checked' => $model->isNewRecord ? true : (bool)$model->activo
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'descripcion_gestion')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= SiteController::actionGet_input_select2(
                        $form,
                        $model,
                        'idlocalidad',
                        'cmb_localidades',
                        $localidades,
                        'id',
                        'localidad'
                    ) ?>
                </div>
            </div>


            <div class="row">
                <div class="col-md-10">
                    <?= $form->field($model, 'direccion_calle')->textInput(['id' => 'input_direccion_calle', 'maxlength' => true]) ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'direccion_altura')->textInput(['id' => 'input_direccion_altura']) ?>
                </div>


            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'direccion')->textInput(['id' => 'input_direccion', 'maxlength' => true]) ?>
                </div>

            </div>

        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <label>Ubicación Geográfica</label>

                    <div id="mapa-contenedor"
                        style="width:100%;height:257px;border:1px solid #ccc;border-radius:4px;">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'geolocalizacion')->textInput() ?>
                </div>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<<JS

let mapa;
let marcador;

function iniciarMapa() {

    if (typeof L === 'undefined') {
        console.error('Leaflet no cargó');
        return;
    }

    let lat = -38.9516;
    let lng = -68.0591;

    let campoGeo = document.getElementById('edificio-geolocalizacion');

    if (!campoGeo) {
        console.error('No existe el campo geolocalizacion');
        return;
    }

    if (campoGeo.value.trim() !== '') {

        let partes = campoGeo.value.split(',');

        if (partes.length === 2) {

            let latTmp = parseFloat(partes[0]);
            let lngTmp = parseFloat(partes[1]);

            if (!isNaN(latTmp) && !isNaN(lngTmp)) {
                lat = latTmp;
                lng = lngTmp;
            }
        }
    }

    mapa = L.map('mapa-contenedor').setView([lat, lng], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

    marcador = L.marker([lat, lng], {
        draggable: true
    }).addTo(mapa);

    campoGeo.value = lat.toFixed(6) + ',' + lng.toFixed(6);

    marcador.on('dragend', function () {

        let pos = marcador.getLatLng();

        campoGeo.value =
            pos.lat.toFixed(6) + ',' +
            pos.lng.toFixed(6);
    });

    mapa.on('click', function (e) {

        marcador.setLatLng(e.latlng);

        campoGeo.value =
            e.latlng.lat.toFixed(6) + ',' +
            e.latlng.lng.toFixed(6);
    });

    setTimeout(function () {
        mapa.invalidateSize();
    }, 500);
}

iniciarMapa();

async function buscarDireccion() {

    let calle = $('#input_direccion_calle').val().trim();
    let altura = $('#input_direccion_altura').val().trim();
    let localidad = $('#cmb_localidades option:selected').text().trim();

    if (!calle || !altura || !localidad) {
        return;
    }

    
    let direccion =
    calle + ' ' +
    altura + ', ' +
    localidad.replace(' Capital', '') +
    ', Confluencia, Argentina';

    console.log('Buscando dirección:', direccion);

    let url =
    'https://nominatim.openstreetmap.org/search?' +
    'format=json' +
    '&countrycodes=ar' +
    '&limit=1' +
    '&q=' + encodeURIComponent(direccion);
    try {

        let response = await fetch(url);

        let data = await response.json();

        console.log(data);

        if (data.length === 0) {
            console.warn('No se encontraron resultados para la dirección');
            return;
        }

        let lat = parseFloat(data[0].lat);
        let lng = parseFloat(data[0].lon);

        console.log('Coordenadas encontradas:', lat, lng);

        marcador.setLatLng([lat, lng]);

        mapa.setView([lat, lng], 17);

        $('#edificio-geolocalizacion').val(
            lat.toFixed(6) + ',' + lng.toFixed(6)
        );

    } catch (e) {
        console.error(e);
    }
}


$('#input_direccion_calle').on('blur', buscarDireccion);

$('#input_direccion_altura').on('blur', buscarDireccion);

$('#cmb_localidades').on('change', buscarDireccion);
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>