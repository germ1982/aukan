<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use app\models\Usuarios;
use yii\helpers\Url;
use kartik\widgets\FileInput;

?>
<style>
    .file-drop-zone{
        min-height: 100px!important;
    }
    .file-preview-image {
        min-height: 100px!important;
        max-width: 100%!important;   /* Ajusta la imagen al 100% del contenedor */
        max-height: 100%!important; /* Define la altura máxima de la vista previa */
        object-fit: cover!important; /* Cubre el contenedor sin distorsión */

    }
    .krajee-default{
        min-height: 100px!important;
        float: none!important;
    }
    .kv-file-content{
        min-height: 100px!important;
        width: 100%!important;
    }
</style>

<div class="usuarios-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]);
    if (!isset($model)) {
        $model = new Usuarios();
    }
    ?>
    <?= $form->field($model, 'idpersona')->hiddenInput(['id' => 'input_idpersona'])->label(false) ?>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <!-- Linea de busqueda -->
                <div class="col-md-4">
                    <div class="input-group">
                        <?= $form->field($model, 'documento')->textInput([
                            'id' => 'input_dni_persona',
                            'onkeyup' => 'ValidarIngresoDni();',
                            //'disabled' => $generada
                        ])
                            ->label($model->isNewRecord ? 'Buscar Persona' : 'DNI Persona') ?>
                        <span class="input-group-btn" style="padding-top:27px;">
                            <?= SiteController::actionGet_boton_buscar_x_documento(
                                'btn_dni',
                                'Buscar Dni',
                                'datos_persona(0);'
                            ) ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-8" style="padding-top:30px;" id="txt_mensaje"></div>
            </div>
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'showClose' => false,
                    'showCancel' => false,
                    'mainClass' => 'input-group-sm',
                    //'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
                    'maxFileSize' => 5000,
                    'fileActionSettings' => [
                        'showRemove' => false,
                        'showUpload' => false,
                        'showZoom' => false,
                        'showCaption' => false,
                        'showCancel' => false
                    ],
                    'previewFileType' => 'file',
                    'layoutTemplates' => [
                        'footer' => '',  // Remueve el footer en la vista previa si es necesario
                    ],
                    'initialPreviewConfig' => [
                        ['width' => '50px'] // Define el ancho de la vista previa
                    ]
                ]
            ]);

            ?>

        </div>
    </div>
</div>








<?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsFile('@web/js/stock.js'); ?>
<?php
$script = <<<JS

function datos_persona() {
        $('#input_idpersona').val('0');
        
        let dni_persona = $("#input_dni_persona").val();

        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }

        $('#txt_mensaje').html("Buscando datos de Persona con dni " + dni_persona);
        $.post("index.php?r=persona/validar_dni&dni=" + dni_persona, function(data) {
            data = $.parseJSON(data);
            console.log("console.log('funcion datos_persona'); // POST a index.php?r=persona/validar_dni&dni=" + dni_persona);
            if (data.length === 0) {
                $('#txt_mensaje').html("No se encontraron datos de Persona con dni " + dni_persona);
                //buscar_en_renaper(dni_persona,tipo_persona);
            } else {
                console.log('funcion datos_persona // encontro');
                console.log(data);
                $('#input_idpersona').val(data[0]['idpersona']);

                aux = data[0]['apellido'] + ', ' + data[0]['nombre'];
                $('#txt_mensaje').html(aux);
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