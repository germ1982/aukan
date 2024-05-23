<?php

use app\models\Sds_gis_capa;
use app\models\Sds_gis_capa_item;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_situacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-org-situacion-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="row" style="height: 20%">
                <div class="col-md-6">
                    <?php
                    if ($model->inicio != null) {
                        $model->inicio = date('d/m/Y', strtotime(str_replace('/', '-', $model->inicio)));
                    }
                    echo $form->field($model, 'inicio')->widget(DatePicker::ClassName(), [
                        'name' => 'check_issue_date',
                        'language' => 'es',
                        'readonly' => false,
                        'layout' => '{picker}{input}{remove}',
                        'options' => [
                            'id' => 'fecha_inicio',
                            'class' => 'form-control input-md',
                            'disabled' => false
                        ],
                        'pluginOptions' => [
                            'value' => null,
                            'format' => 'dd/mm/yyyy',
                            'endDate' => date('d/m/Y'),
                            'todayHighlight' => true,
                            'autoclose' => true,
                        ]
                    ])->label('Inicio'); ?>
                </div>
                <div class="col-md-6">
                    <?php
                    if ($model->fin != null) {
                        $model->fin = date('d/m/Y', strtotime(str_replace('/', '-', $model->fin)));
                    }
                    echo $form->field($model, 'fin')->widget(DatePicker::ClassName(), [
                        'name' => 'check_issue_date',
                        'language' => 'es',
                        'readonly' => false,
                        'layout' => '{picker}{input}{remove}',
                        'options' => [
                            'id' => 'fecha_fin',
                            'class' => 'form-control input-md',
                            'disabled' => false
                        ],
                        'pluginOptions' => [
                            'value' => null,
                            'format' => 'dd/mm/yyyy',
                            'todayHighlight' => true,
                            'autoclose' => true,
                        ]
                    ])->label('Finalización'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'idcapaitem')->dropdownList(
                        ArrayHelper::map(
                            Sds_gis_capa_item::find()->where('idcapa>1')->orderBy(['idcapa' => SORT_ASC, 'descripcion' => SORT_ASC])->all(),
                            'idcapaitem',
                            function ($model) {
                                $capa = Sds_gis_capa::findOne($model->idcapa)->descripcion;
                                return $capa . " - " . $model->descripcion;
                            }
                        ),
                        [
                            'prompt' => 'Seleccionar Lugar a Asignar ...',
                            'id' => 'cmb_edificio'
                        ]
                    );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'profesional_firma')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'dias_horarios')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'funcion')->textarea(['rows' => 4]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'detalles')->textarea(['rows' => 4]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <?php
                if ($model->path == null) {
                    echo $form->field(
                        $model,
                        'temp_archivo_adjunto',
                        ['enableClientValidation' => true, 'enableAjaxValidation' => true]
                    )
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                            'language' => 'es',
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                                'showCaption' => false,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showClose' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_org_situacion/update']),
                                'maxFileSize' => 100000,
                                'previewFileType' => 'file',
                                'initialCaption' => $model->path,
                                'fileActionSettings' => [
                                    'showRemove' => true,
                                    'showUpload' => false,
                                ]
                            ],
                        ]);
                } else {
                    echo $form->field($model, 'temp_archivo_adjunto', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                            'language' => 'es',
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showClose' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_org_situacion/update']),
                                'maxFileSize' => 100000,
                                'previewFileType' => 'file',
                                'initialPreview' => [
                                    Html::img($model->path, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                ],
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'initialCaption' => $model->path,
                                'fileActionSettings' => [
                                    'showRemove' => true,
                                    'showUpload' => false,
                                ]
                            ],
                            'pluginEvents' => [
                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                "filereset" => "function() {  }",
                            ]
                        ]);
                }
                ?>
            </div>
            <div class="row">
                <section class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div id="direccion" class="col-md-12">
                                <?= $form->field($model, 'direccion')->textInput(['id' => 'txtDireccion', 'maxlength' => true, 'readonly' => true]) ?>
                            </div>
                        </div>
                        <div id="map" class="box-body">

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
                    $("#direccion").show();
                    $("#txtDireccion").val(data['direccion']);
                    $("#map").show();
                    setMapProperties();
                }
            );
        } else {
            $("#map").html('');
            $("#direccion").hide();
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