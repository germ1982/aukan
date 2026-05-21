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

// Leaflet
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
$this->registerJsFile(
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
    ['position' => \yii\web\View::POS_END]
);
?>

<div class="edificio-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-7">
                    <?= $form->field($model, 'descripcion_fija')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-2" style="padding-top:30px;">
                    <?= $form->field($model, 'activo')->checkbox([
                        'checked' => $model->isNewRecord ? true : (bool)$model->activo
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <?= $form->field($model, 'descripcion_gestion')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-5">
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
                <div class="col-md-5">
                    <?= $form->field($model, 'direccion_calle')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'direccion_altura')->textInput() ?>
                </div>

                <div class="col-md-5">
                    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'geolocalizacion')->textarea(['rows' => 3]) ?>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <label>Ubicación Geográfica</label>

            <div id="mapa-contenedor"
                 style="width:100%;height:350px;border:1px solid #ccc;border-radius:4px;">
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    let lat = -38.9516;
    let lng = -68.0591;

    let campoGeo = document.getElementById('edificio-geolocalizacion');

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

    let mapa = L.map('mapa-contenedor').setView([lat, lng], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

    let marcador = L.marker([lat, lng], {
        draggable: true
    }).addTo(mapa);

    marcador.on('dragend', function () {

        let pos = marcador.getLatLng();

        campoGeo.value =
            pos.lat.toFixed(6) + ',' + pos.lng.toFixed(6);
    });

    mapa.on('click', function (e) {

        marcador.setLatLng(e.latlng);

        campoGeo.value =
            e.latlng.lat.toFixed(6) + ',' +
            e.latlng.lng.toFixed(6);
    });

});
</script>

