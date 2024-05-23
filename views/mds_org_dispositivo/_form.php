<?php

use app\models\Mds_org_organismo;
use app\models\Sds_gis_capa_item;
use kartik\select2\Select2;
use pigolab\locationpicker\CoordinatesPicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_dispositivo */
/* @var $form yii\widgets\ActiveForm */
$organigrama = isset($organigrama) ? $organigrama:false;

?>

<div class="mds-org-dispositivo-form">

    <?php $form = ActiveForm::begin(['action' => ['mds_org_dispositivo/' . ($model->isNewRecord ? 'create' . (isset($botones) ? '_ext' : '') : 'update'), 'id' => $model->iddispositivo,'organigrama'=>$organigrama], 'id' => $model->formName()]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 col-md-offset-10" style="text-align: right;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'idorganismo')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(
                    Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idorganismo',
                    'descripcion'
                ),
                'options' => ['placeholder' => 'Seleccionar Organismo ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'idcapaitem')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(
                    Sds_gis_capa_item::find()->where("idcapa<>1")->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idcapaitem',
                    'descripcion'
                ),
                'options' => ['placeholder' => 'Seleccionar Edificio ...',  'id' => 'cmb_edificio'],
                'pluginOptions' => [
                    'allowClear' => true
                ],                
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="map" class="box-body">

            </div>
        </div>
    </div>
    <?php if (isset($botones)) { ?>
        <br>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::button('Cerrar', [
                'class' => 'btn btn-default',
                'onclick' => '$("#abm_dispositivo").hide();
                console.log($("#abm_contacto").length);
                if($("#abm_contacto").length==0){
                    $("#btnGuardar").show();
                    $("#btnCerrar").show();
                }
                $("#btnGuardarInterno").show();
                $("#btnCerrarInterno").show();'
            ]);
            ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<  JS

$('form#{$model->formName()}').on('beforeSubmit',function(e){
    
    var \$form = $(this);
    $.post(

        \$form.attr("action"),
        \$form.serialize()

    )
    .done(function(result){    
        if(result > 0){
            $(\$form).trigger("reset");      
            $('#abm_dispositivo').hide(); 
            e.preventDefault();
            $.post("index.php?r=mds_org_dispositivo/cmb_dispositivo", function(data) {
                $("select#cmb_dispositivo").html(data);
                $("select#cmb_dispositivo").val(result);
                if($("#abm_contacto").length==0){
                    $("#btnGuardar").show();
                    $("#btnCerrar").show();
                }
                $("#btnGuardarInterno").show();
                $("#btnCerrarInterno").show();
            });            
        }else{
            $("#message").html(result);
        }
    }).fail(function(){
        console.log("server error");
    });
   
    return false;
});
    

JS;

$this->registerJs($script);
$this->registerCssFile('css/mapa_edificios.css');
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg&callback=cargarMapa');
?>
<script>
    var map;
    var infoWindow = null;
    var latitud, longitud, detalle = null;
    $(document).ready(function() {
        cargarMapa();
    });
    $("#cmb_edificio").change(function() {
        cargarMapa();
    });

    function cargarMapa() {
        var idcapaitem = $("#cmb_edificio").val();
        if (idcapaitem != '') {
            $.getJSON("consultas/str_capa_item.php", {
                    'idcapaitem': idcapaitem
                },
                function(data) {
                    latitud = data['latitud'];
                    longitud = data['longitud'];
                    detalle = data['descripcion'];
                    $("#map").show();
                    setMapProperties();
                }
            );
        } else {
            $("#map").html('');
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