<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use yii\helpers\Url;
use kartik\widgets\FileInput;

?>

<div class="usuarios-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="row" >
                <!-- Linea de busqueda -->
                <div class="col-md-4">
                    <div class="input-group">
                        <?= $form->field($model, 'documento')->textInput([
                            'id' => 'input_dni_persona',
                            //'onkeyup' => 'ValidarIngresoDni(0);',
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
            <?php
            if ($model->avatar == null) {
                echo $form->field($model, 'avatar', [
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false
                ])
                    ->widget(FileInput::classname(), [
                        //'name' => 'i1',
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            //'showPreview' => false,
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showClose' => false,
                            'showCancel' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/mds_rum_novedad/update']),
                            'maxFileSize' => 1000,
                            /* 'initialPreview'=>[
                                                        Html::img($model->Foto,['class'=>'file-preview-image']),
                                                        ], */
                            'previewFileType' => 'image',
                            'initialCaption' => $model->avatar,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                                'showZoom' => false,
                                'showCaption' => false,
                                'showCancel' => false
                            ]
                            //'minFileCount' => 1,
                            // 'validateInitialCount' => true,
                        ],
                    ])->label('IMAGEN PRINCIPAL');
            } else {
                $archivo = "/img/usuarios-avatares/$model->avatar.jpg";
                echo $form->field($model, 'avatar', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showClose' => false,
                            'showCancel' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/mds_rum_novedad']),
                            'maxFileSize' => 1000,
                            'previewFileType' => 'image',
                            'initialPreview' => [
                                //Html::img($model->avatar, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                Html::img(Url::base() . $archivo, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->avatar);
                            ],
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'initialCaption' => $model->avatar,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                                'showZoom' => false,
                                'showCaption' => false,
                                'showCancel' => false
                            ]
                        ],
                        'pluginEvents' => [
                            "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                            "filereset" => "function() {  }",
                        ]
                    ])->label('IMAGEN PRINCIPAL');
            }
            ?>
        </div>
    </div>
</div>








<?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsFile('@web/js/stock.js'); ?>
<?php
$script = <<<JS

function datos_persona(tipo_persona) {
        console.log('funcion datos_persona');
        tipo_persona_string = tipo_persona==0 ? 'destinatario' : 'retira';

        $('#input_idpersona_' + tipo_persona_string).val('0');
        
        let dni_persona = $("#input_dni_persona_" + tipo_persona_string).val();

        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }

        $('#input_numero_documento_' + tipo_persona_string).val(dni_persona);
        $('#input_numero_documento_' + tipo_persona_string).prop("readonly", true);

        $('#txt_mensaje_' + tipo_persona_string).html("Buscando datos de Persona con dni " + dni_persona);
        $.post("index.php?r=sds_com_persona/validar_dni&dni=" + dni_persona, function(data) {
            data = $.parseJSON(data);
            console.log("console.log('funcion datos_persona'); // POST a index.php?r=sds_com_persona/validar_dni&dni=" + dni_persona);
            if (data.length === 0) {
                console.log('funcion datos_persona // no encontro');

                BloquearControlesPersona(false,tipo_persona);
                LimpiarCamposAltaPersona(dni_persona,tipo_persona);
                $("#div_datos_persona_" + tipo_persona_string).show();
                buscar_en_renaper(dni_persona,tipo_persona);
            } else {
                console.log('funcion datos_persona // encontro');
                console.log(data);
                $('#input_idpersona_' + tipo_persona_string).val(data[0]['idpersona']);
                $('#input_combo_nacionalidad_' + tipo_persona_string).val(data[0]['nacionalidad']);
                $('#input_combo_genero_' + tipo_persona_string).val(data[0]['genero']);
                $('#input_apellido_' + tipo_persona_string).val(data[0]['apellido']);
                $('#input_nombre_' + tipo_persona_string).val(data[0]['nombre']);
                $('#input_combo_tipo_documento_' + tipo_persona_string).val(data[0]['documento_tipo']);
                $('#input_fecha_nacimiento_' + tipo_persona_string).val(formatearFecha(data[0]['fecha_nacimiento']));
                $('#input_numero_calle_' + tipo_persona_string).val(data[0]['domicilio_numero']);
                $('#input_calle_' + tipo_persona_string).val(data[0]['domicilio_calle']);
                $('#combo_localidad_' + tipo_persona_string).val(data[0]['idlocalidad']).trigger("change");
                
                BloquearControlesPersona(true,tipo_persona);
                //buscar_foto_en_renaper(dni_persona,tipo_persona);
                aux = tipo_persona_string.toUpperCase() + ": " + data[0]['apellido'] + ', ' + data[0]['nombre'];
                $('#txt_mensaje_' + tipo_persona_string).html(aux);

                

                if(tipo_persona==0)
                    {
                        let aux_dni_retira = $('#input_dni_persona_retira').val();
                        if(aux_dni_retira=='')
                        {clonar_datos_destinatario(dni_persona);}
                        else
                        {refrescar_retira();}
                        BloquearControlesPersona(true,1);
                    }
            }

        });


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