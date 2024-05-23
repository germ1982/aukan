<?php

use app\models\Mds_org_organismo;
use app\models\Sds_gis_capa_item;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_dispositivo */
?>
<div class="mds-org-dispositivo-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'descripcion',
            [
                'attribute' => 'idorganismo',
                'value' => function ($model) {
                    $idorganismo = $model->idorganismo;
                    if ($idorganismo != null) {
                        $organismo = Mds_org_organismo::findOne($idorganismo);
                        return $organismo->descripcion;
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'idcapaitem',
                'format'=>'html',
                'value' => function ($model) {
                    $idcapaitem = $model->idcapaitem;
                    if ($idcapaitem != null) {
                        $edificio = Sds_gis_capa_item::findOne($idcapaitem);
                        return $edificio->descripcion;
                    }
                    return "";
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'activo',
                'value' => function ($model) {
                    return $model->activo == 1 ? 'Si' : 'No';
                },
                'width' => '8%',
                'filter' => ['0' => 'No', '1' => ' Si']
            ],
        ],
    ]) ?>    
    <div id="map" class="box-body"></div>
</div>
<?php
$this->registerCssFile('css/mapa_edificios.css');
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg&callback=cargarMapa');
?>
<script>
    var map;
    var infoWindow = null;
    var latitud, longitud, detalle = null;
    var idcapaitem=<?php echo $model->idcapaitem ?>;

    $(document).ready(function() {        
        cargarMapa();
    });

    function cargarMapa() {
        if (idcapaitem != '') {
            $.getJSON("consultas/str_capa_item.php", {
                    'idcapaitem': idcapaitem
                },
                function(data) {
                    latitud = data['latitud'];
                    longitud = data['longitud'];
                    detalle = data['descripcion'];
                    setMapProperties();
                }
            );
        }
    }

    function setMapProperties() {
        if (latitud == null) {
            latitud = -38.95167840000001;
        }
        if (longitud == null) {
            longitud = -68.05918880000002;
        }
        if (detalle == null) {
            detalle = "Prueba";
        }
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
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
            title: detalle
        });

        html = "<div>" + detalle + "</div>";

        bindInfoWindow(marker, map, infoWindow, html);

        var marker_item = new Object();
        marker_item.marker = marker;
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
            /*if (marker.getAnimation() !== null) {
             marker.setAnimation(null);
             } else {
             marker.setAnimation(google.maps.Animation.BOUNCE);
             }*/
        });
    }
</script>