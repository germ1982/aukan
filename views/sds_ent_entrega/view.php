<?php

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_entrega */

use app\models\Mds_org_informe;
use app\models\Mds_org_organismo_externo;
use app\models\Mds_seg_usuario;
use app\models\Sds_ent_tipo;
use yii\bootstrap\Collapse;
use yii\jui\Accordion;

$this->title = "Consulta de Entrega";
?>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Dni: <?= $model->dni ?></h2>
            </header>
            <div class="panel-body">
                <div class="row">
                    <?php
                    //Trampita para que anden los accordion del template con yii ;)
                    echo Collapse::widget([]); ?>
                    <div class="col-md-12">
                        <div class="panel-group" id="accordion_renaper">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#renaper">
                                            Registro Renaper
                                        </a>
                                    </h4>
                                </div>
                                <div id="renaper" class="accordion-body collapse in">
                                    <div class="panel-body" id="renaper_content">
                                        <div class="row">
                                            <!-- <div class="col-md-3" style="text-align: center;">
                                                <img id="renaper_foto" src="" alt="" height="200px" />
                                            </div> -->
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12" id="renaper_nombre"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12" id="renaper_apellido"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12" id="renaper_domicilio"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12" id="renaper_localidad"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion_entrega">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#entrega">
                                            Datos Entrega
                                        </a>
                                    </h4>
                                </div>
                                <div id="entrega" class="accordion-body collapse in">
                                    <div class="panel-body">
                                        <?php
                                        $usuario = Mds_seg_usuario::findOne($model->idusuario);
                                        ?>
                                        <div class="col-md-12">
                                            <?php if ($model->numero != null) : ?>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <?= "<b>Número: </b>" . $model->numero; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <?= "<b>Cantidad: </b>" . $model->cantidad; ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= "<b>Tipo: </b>" . Sds_ent_tipo::findOne($model->idtipo)->descripcion; ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= "<b>Usuario: </b>" . $usuario->user; ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= "<b>Entidad: </b>" .  Mds_org_organismo_externo::findOne($usuario->externo)->descripcion; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= "<b>Detalle: </b>" . $model->observaciones ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion_fotos_dni">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#fotos_dni">
                                            Fotos DNI
                                        </a>
                                    </h4>
                                </div>
                                <div id="fotos_dni" class="accordion-body collapse in">
                                    <div class="panel-body" id="renaper_content">
                                        <div class="row">
                                            <div class="col-md-6" style="text-align: center;">
                                                <div class="row">
                                                    <h2 class="panel-title">Frente</h2>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <img id="dni_frente" src="<?= $model->dni_frente ?>" alt="" height="200px" />
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="text-align: center;">
                                                <div class="row">
                                                    <h2 class="panel-title">Dorso</h2>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <img id="dni_dorso" src="<?= $model->dni_dorso ?>" alt="" height="200px" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display:<?= $model->acta ? "block" : "none" ?>">
                            <div class="panel-group" id="accordion_fotos_dni">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#fotos_dni">
                                                Archivo de Acta
                                            </a>
                                        </h4>
                                    </div>
                                    <?php if (Mds_org_informe::getExtension($model->acta) != 'image') : ?>
                                        <div class="row">
                                            <div class='col-md-12' style="padding: 1rem;text-align:center;">
                                                <object width="80%" height="600px" type="application/pdf" data="<?php echo $model->acta; ?>">
                                                    <p>Archivo Adjunto no disponible.</p>
                                                </object>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="row">
                                            <div class='col-md-12' style="padding: 1rem">
                                                <img id="acta" src="<?= $model->acta ?>" alt="" height="200px" />
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion_ubicacion">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#ubicacion">
                                            Ubicación
                                        </a>
                                    </h4>
                                </div>
                                <div id="ubicacion" class="accordion-body collapse in">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="map" class="box-body">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>

            </div>
        </section>
    </div>
</div>
<?php
$this->registerCssFile('css/mapa_edificios.css');
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg&callback=cargarMapa');
$this->registerJs(
    "
    $(document).ready(function() {
        cargarMapa();
                        datos_renaper();                        
                    });"
);

?>
<script>
    function cargarMapa() {
        $('#map').show();
        setMapProperties();
    }

    function datos_renaper() {
        var dni = <?php echo $model->dni ?>;
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            if (data.status != "error") {
                var nombre = "";
                var apellido = "";
                var domicilio = "";
                var localidad = "";
                var foto = "";
                $.each(data, function(ind, elem) {
                    console.log(ind);
                    if (ind == 'records') {
                        console.log(elem[0]);
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
                        domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                        localidad = elem[0].result.ciudad;
                        //foto = elem[0].result.foto;
                    }
                });

                $("#renaper_nombre").html("<b>Nombre: </b>" + nombre);
                $("#renaper_apellido").html("<b>Apellido: </b>" + apellido);
                $("#renaper_domicilio").html("<b>Domicilio: </b>" + domicilio);
                $("#renaper_localidad").html("<b>Localidad: </b>" + localidad.replace("ï¿½", "É").replace(/_/g, " "));
               /*  $("#renaper_foto").attr("src", foto); */
            }
        });
    }

    var map;
    var infoWindow = null;
    var latitud, longitud, detalle = null;

    function setMapProperties() {
        latitud = <?= $model->latitud; ?>;
        longitud = <?= $model->longitud; ?>;
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