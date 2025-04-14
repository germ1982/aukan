<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Persona;
use app\models\RegistroFamiliaLegajo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;

if (isset($model->idpersona)) {
    $persona = Persona::findOne($model->idpersona);
    $model->dni = $persona->documento;
    $model->apellido = "$persona->apellido";
    $model->nombre = "$persona->nombre";
}

if (isset($model->archivo_adjunto)) {
    $imagePath = Url::to('uploads_datafam/registro_familia_legajos/' . $model->archivo_adjunto);

    $initialPreview = [
        Html::img($imagePath, ['class' => 'file-preview-image', 'alt' => 'Imagen', 'title' => $model->archivo_adjunto, 'width' => '100%', 'height' => 'auto']),
    ];
}
$tipos_legajos = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_LEGAJO);

$script = <<<JS

function datos_persona() {
        $('#input_idpersona').val('0');
        
        let dni_persona = $("#input_dni_persona").val();

        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }

        $('#txt_mensaje').html("Buscando...");
        $.post("index.php?r=persona/validar_dni&dni=" + dni_persona, function(data) {
            data = $.parseJSON(data);
            console.log(data);
            console.log("console.log('funcion datos_persona'); // POST a index.php?r=persona/validar_dni&dni=" + dni_persona);
            if (data.length === 0) {
                $('#txt_mensaje').html("No cargado");
                //buscar_en_renaper(dni_persona,tipo_persona);
            } else {
                $('#input_idpersona').val(data[0]['idpersona']);
                $('#input_nombre').val(data[0]['nombre']);
                $('#input_apellido').val(data[0]['apellido']);
                $('#txt_mensaje').html("");
            }

        });


    }

    function ValidarIngresoDni() {
        var aux = event.which;

        if (aux == 13) //pregunto si fue el enter
        {
            datos_persona();
        }
    }
function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }
JS;
$this->registerJs($script);
?>

<style>
    .file-drop-zone {
        min-height: 200px !important;
        /* Aumenta la altura del área de carga */
        height: 280px !important;
    }

    .file-preview-object {
        height: 300px !important;
        /* Ajusta la altura según necesites */
    }

    .file-preview-frame object {
        height: 230px !important;
    }

    .file-preview-image {
        height: 100% !important;
        min-height: 100px !important;
        /* max-width: 100% !important; */
        /* Ajusta la imagen al 100% del contenedor */
        max-height: 100% !important;
        /* Define la altura máxima de la vista previa */
        object-fit: cover !important;
        /* Cubre el contenedor sin distorsión */

    }

    .file-preview-thumbnails {
        height: 250 !important;
    }

    .krajee-default {
        min-height: 100px !important;
        float: none !important;
        height: 100% !important;
    }

    .kv-file-content {
        min-height: 100px !important;
        width: 100% !important;
        height: 200px !important;

    }

    .linea_busqueda {
        margin-top: -20px;
    }
</style>

<div class="registro-familia-legajo-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'], // Esto permite cargar archivos
    ]); ?>

    <?= $form->field($model, 'idpersona')->hiddenInput(['id' => 'input_idpersona'])->label(false) ?>
    <div class="row linea_busqueda">
        <div class=" col-md-5">
            <div class="row">
                <div class=" col-md-4">
                    <?= $form->field($model, 'num_legajo')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-5">
                    <div class="input-group">
                        <?= $form->field($model, 'dni')->textInput([
                            'id' => 'input_dni_persona',
                            'onkeyup' => 'ValidarIngresoDni();',
                            'onblur' => 'datos_persona();',
                        ])
                            ->label($model->isNewRecord ? 'Buscar Por DNI' : 'DNI Persona') ?>
                        <span class="input-group-btn" style="padding-top:27px;">
                            <?= SiteController::actionGet_boton_buscar_x_documento(
                                'btn_dni',
                                'Buscar Dni',
                                'datos_persona();'
                            ) ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top:30px;" id="txt_mensaje"></div>
            </div>
            <div class="row">
                <div class=" col-md-5">
                    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true, 'id' => 'input_apellido']) ?>
                </div>
                <div class=" col-md-7">
                    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, 'id' => 'input_nombre']) ?>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-6">
                    <?= SiteController::actionGet_input_select($form, $model, 'tipo_legajo', 'cmb_tipo_legajo', $tipos_legajos, 'id_configuracion', 'descripcion', 'Tipo de Legajo', 'Seleccione un Tipo...') ?>
                </div>
            </div>


        </div>
        <div class=" col-md-7">

            <?php
            $ts = time();
            $urlArchivo = Yii::$app->request->hostInfo . '/uploads_datafam/registro_familia_legajos/' . $model->archivo_adjunto . '?t=' . $ts;
            
            //$urlArchivo = Yii::$app->request->hostInfo . '/uploads_datafam/registro_familia_legajos/' . $model->archivo_adjunto;
            $urlArchivoConTimestamp = $urlArchivo . '?v=' . time(); // 👈 fuerza refresco
            //echo "<p>URL generada: <a href='$urlArchivo' target='_blank'>$urlArchivo</a></p>";
            ?>
            <?= $form->field($model, 'archivo_adjunto_file')->widget(FileInput::classname(), [
                'options' => ['accept' => '.pdf'],
                'pluginOptions' => [
                    'initialPreviewAsData' => true, // esto le dice al plugin que es una URL o path al archivo
                    'initialPreview' => $model->archivo_adjunto
                        ? [$urlArchivo]
                        : [],
                    'initialPreviewFileType' => 'pdf',
                    'allowedFileExtensions' => ['pdf'],
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'initialPreviewConfig' => $model->archivo_adjunto ? [[
                        'type' => 'pdf',
                        'caption' => $model->archivo_adjunto,
                        'downloadUrl' => $urlArchivo
                    ]] : [],
                ],
            ]); ?>



        </div>
    </div>





    <?php ActiveForm::end(); ?>

</div>