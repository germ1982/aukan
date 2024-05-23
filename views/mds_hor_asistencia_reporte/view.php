<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_asistencia_reporte */
?>
<div class="mds-hor-asistencia-reporte-view">
    <div class='row'>
        <div class='col-md-4'>
            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:100%;height:auto;max-height:350px;' id='base64image' src='data:image/png;base64,<?= $model->foto ?>' />
        </div>
        <div class='col-md-8'>
            <div id="map" class="box-body" style="width:100%;height:500px"></div>
        </div>
    </div>
</div>
<?php
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg&callback=setMapProperties');
?>

<script>
    var map;
    var infoWindow = null;
    var latitud = <?php echo $model->latitud ?>;
    var longitud = <?php echo $model->longitud ?>;

    $(document).ready(function() {
        setMapProperties();
    });

    function setMapProperties() {
        if (latitud == null) {
            latitud = -38.95167840000001;
        }
        if (longitud == null) {
            longitud = -68.05918880000002;
        }
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            gestureHandling: 'greedy',
            center: new google.maps.LatLng(latitud, longitud),
            mapTypeId: 'roadmap'
        });
        if (infoWindow == null) {
            infoWindow = new google.maps.InfoWindow;
        }
        var latLng = new google.maps.LatLng(latitud, longitud);
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
        });
        var height = parseInt($('#base64image').css('height'));
        $("#map").css('height', height);
    }
</script>