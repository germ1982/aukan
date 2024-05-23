<?php

use app\controllers\Mds_org_contactoController;
use app\controllers\Sds_com_configuracionController;
use app\controllers\Sds_com_localidadController;
use app\controllers\SiteController;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Mds_org_contacto;
use app\models\Sds_stk_entrega_item;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_deposito;
use app\models\Sds_stk_movimiento;
use app\models\Sds_stk_recepcion;
use app\models\Sds_stk_recepcion_item;
use kartik\time\TimePicker;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_stk_entrega;
use Da\QrCode\Label;
use yii\helpers\Url;
use kartik\widgets\FileInput;

/* NOTA MENTAL:
0=destinatario
1=retirante */

function GetFechaActual()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $mydate = getdate(date('U'));

    $dia = $mydate['mday'];
    if ($dia <= 9) {
        $dia = '0' . $dia;
    }

    $mes = $mydate['mon'];
    if ($mes <= 9) {
        $mes = '0' . $mes;
    }

    $hora = $mydate['hours'];
    if ($hora <= 9) {
        $hora = '0' . $hora;
    }

    $minuto = $mydate['minutes'];
    if ($minuto <= 9) {
        $minuto = '0' . $minuto;
    }

    $Fecha = "$dia/$mes/$mydate[year]";
    //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
    return $Fecha;
}

if ($model->isNewRecord) {
    $model->acta_original = 1;
}

//Si existe fecha_hora, seteo los atributos fecha y hora para usar con los widgets
if ($model->fecha_hora != null) {
    $fecha = $model->fecha_hora;
    $model->fecha_hora = date(
        'd/m/Y',
        strtotime(str_replace('/', '-', $fecha))
    );
    $model->hora = date('H:i', strtotime($fecha));
} else {
    $model->hora = date('H:i');
    $model->fecha_hora = date('d/m/Y');
}
//Si existe la destinatario, seteo el atributo documento para usar en el filtro de busqueda
if ($model->idpersona) {
    $destinatario = Sds_com_persona::findOne($model->idpersona);
    $documento_destinatario = $destinatario->documento;
    $model->documento = $documento_destinatario;
}
//Si existe la persona que retira, seteo el atributo documento_retira para usar en el filtro de busqueda
if ($model->persona_retira) {
    $persona_retira = Sds_com_persona::findOne($model->persona_retira);
    $documento_retira = $persona_retira->documento;
    $model->documento_retira = $documento_retira;
}

if (!Yii::$app->request->isAjax) {
    echo $model->identrega
        ? "EDITANDO ENTREGA NUMERO $model->identrega"
        : 'NUEVA ENTREGA DE STOCK';
}


$model->temp_archivo_adjunto_entrega = 1;


?>



<div class="sds-stk-entrega-form" id='id_formulario_entrega'>

    <?php $form = ActiveForm::begin(); ?>
    <div id='div_campos' style=<?= $items == 1 ? 'display:none' : '' ?>>
        <?= $form
            ->field($model, 'adjunto_acta_entrega')
            ->hiddenInput(['id' => 'hidden_input_adjunto_acta_entrega'])
            ->label(false) ?>
        <?= $form
            ->field($model, 'identrega')
            ->hiddenInput(['id' => 'hidden_input_id_entrega'])
            ->label(false) ?>
        <div class="row">
            <!-- LINEA 1 -->
            <div class="col-md-3">
                <!-- FECHA -->
                <?= SiteController::actionGet_input_fecha(
                    $form,
                    $model,
                    'fecha_hora',
                    'input_fecha_hora',
                    'Fecha',
                    $generada,
                    null
                ) ?>
            </div>
            <div class="col-md-2">
                <!-- HORA -->
                <?php $model->fecha_hora = date('d/m/Y H:i'); ?>
                <?= SiteController::actionGet_input_hora(
                    $form,
                    $model,
                    'hora',
                    'horax',
                    'Hora',
                    $generada
                ) ?>
            </div>
            <div class="col-md-7">
                <!-- CONTACTO RESPONSABLE -->
                <?= Mds_org_contactoController::actionGet_cmb_contacto(
                    $form,
                    $model,
                    'idcontacto',
                    'combo_responsable',
                    'Responsable',
                    $generada
                ) ?>
            </div>
        </div>
        <!-- LINEA 2 ############################################################################################################## -->
        <div class="row">
            <div class="col-md-6">
                <!-- CONTACTO ENTREGA -->
                <?= Mds_org_contactoController::actionGet_cmb_contacto(
                    $form,
                    $model,
                    'contacto_entrega',
                    'combo_contacto_entrega',
                    'Entrega',
                    $generada
                ) ?>
            </div>

            <div class="col-md-3" style="padding-top:30px">
                <?= $form->field($model, 'referente')->checkbox(['disabled' => $generada]) ?>
            </div>
            <div class="col-md-3" style="padding-top:30px">
                <?= $form->field($model, 'es_organizacion_social')->checkbox(['onchange' => 'mostrar_organizacion_social();', 'id' => 'check_organizacion_social']) ?>
            </div>

        </div>
        <div class="row" id="div_organizacion_social">
            <div class="col-md-7">
                <?= SiteController::actionGet_input_select2($form, $model, 'organizacion_social', 'cmb_organizacion_social', Sds_com_configuracion::find()->where("activo = 1 and idconfiguraciontipo = " . Sds_com_configuracion_tipo::ORGANIZACION_SOCIAL)->all(), 'idconfiguracion', 'descripcion', 'Organizacion Social', 'Organizacion Social ...') ?>
            </div>
        </div><br>

        <!-- BLOQUE 2 BUSQUEDA DESTINATARIO ############################################################################################################## -->
        <div style='border: 1px solid #ccc; border-radius: 4px;padding:5px'>
            <div class="row" style='padding-left:10px; '>
                <!-- Linea de busqueda -->
                <div class="col-md-4">
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'documento')
                            ->textInput([
                                'id' => 'input_dni_persona_destinatario',
                                'onkeyup' => 'ValidarIngresoDni(0);',
                                'disabled' => $generada
                            ])
                            ->label(
                                $model->isNewRecord
                                    ? 'Buscar Destinatario por Dni'
                                    : 'DNI Destinatario'
                            ) ?>
                        <span class="input-group-btn" style="padding-top:27px;">
                            <?= SiteController::actionGet_boton_buscar_x_documento(
                                'btn_dni',
                                'Buscar Dni',
                                'datos_persona(0);'
                            ) ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-8" style="padding-top:30px;" id="txt_mensaje_destinatario"></div>
            </div>
            <!-- DATOS ALTA DE DESTINATARIO  -->
            <!-- <div id="div_datos_persona_destinatario" style="display:none;"> -->
            <div id="div_datos_persona_destinatario">
                <div class="row" style='padding-left:10px; padding-top:10px;'>
                    <div class="col-md-12">
                        <!-- Columna Datos -->

                        <div class="row">
                            <!-- Linea 1 Datos Destinatario-->
                            <div class="col-md-4">
                                <!-- Apellido -->
                                <?= $form
                                    ->field($model, 'apellido')
                                    ->textInput([
                                        'id' => 'input_apellido_destinatario',
                                    ])
                                    ->label('Apellido') ?>
                            </div>
                            <div class="col-md-8">
                                <!-- Nombres -->
                                <?= $form
                                    ->field($model, 'nombre')
                                    ->textInput([
                                        'id' => 'input_nombre_destinatario',
                                    ])
                                    ->label('Nombres') ?>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Linea 2 Datos Destinatario-->
                            <div class="col-md-2">
                                <!-- id persona -->
                                <?= $form
                                    ->field($model, 'idpersona')
                                    ->textInput([
                                        'id' => 'input_idpersona_destinatario',
                                        'readonly' => true,
                                    ])
                                    ->label('Id Persona') ?>
                            </div>
                            <div class="col-md-4">
                                <!-- tipo documento -->
                                <?= Sds_com_configuracionController::actionGet_cmb_dropdown_configuracion(
                                    $form,
                                    $model,
                                    'documento_tipo',
                                    'input_combo_tipo_documento_destinatario',
                                    'Tipo Documento',
                                    Sds_com_configuracion_tipo::TIPO_TIPO_DOC
                                ) ?>
                            </div>
                            <div class="col-md-3">
                                <!-- documento -->
                                <?= $form
                                    ->field($model, 'documento')
                                    ->textInput([
                                        'id' =>
                                        'input_numero_documento_destinatario',
                                    ])
                                    ->label('Numero Documento') ?>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Linea 3 Datos Destinatario-->
                            <div class="col-md-4">
                                <!-- fecha de nacimiento -->
                                <?= SiteController::actionGet_input_fecha(
                                    $form,
                                    $model,
                                    'fecha_nacimiento',
                                    'input_fecha_nacimiento_destinatario',
                                    'Fecha de Nacimiento',
                                    $generada,
                                    null
                                ) ?>
                            </div>
                            <div class="col-md-4">
                                <!-- Nacionalidad -->
                                <?= Sds_com_configuracionController::actionGet_cmb_dropdown_configuracion(
                                    $form,
                                    $model,
                                    'nacionalidad',
                                    'input_combo_nacionalidad_destinatario',
                                    'Nacionalidad',
                                    Sds_com_configuracion_tipo::TIPO_NACIONALIDAD
                                ) ?>
                            </div>
                            <div class="col-md-4">
                                <!-- Genero -->
                                <?= Sds_com_configuracionController::actionGet_cmb_dropdown_configuracion(
                                    $form,
                                    $model,
                                    'genero',
                                    'input_combo_genero_destinatario',
                                    'Genero',
                                    Sds_com_configuracion_tipo::TIPO_GENERO
                                ) ?>
                            </div>
                        </div>

                    </div>
                    <!-- <div class="col-md-3"style="text-align: center;padding: top 50px;">
                                <img id="renaper_foto_destinatario" src="" alt="" height="200px" />
                            </div> -->
                </div>
                <div class="row" style='padding-left:10px;'>
                    <!-- Linea 4 Datos Destinatario-->
                    <div class="col-md-5">
                        <!-- localidades -->
                        <?= Sds_com_localidadController::actionGet_cmb_localidad(
                            $form,
                            $model,
                            'localidad',
                            'combo_localidad_destinatario',
                            'Localidad',
                            58
                        ) ?>
                    </div>
                    <div class="col-md-5">
                        <!-- Calle -->
                        <?= $form
                            ->field($model, 'calle')
                            ->textInput(['id' => 'input_calle_destinatario'])
                            ->label('Calle') ?>
                    </div>
                    <div class="col-md-2">
                        <!-- Numero -->
                        <?= $form
                            ->field($model, 'numero_calle')
                            ->textInput([
                                'id' => 'input_numero_calle_destinatario',
                            ])
                            ->label('Numero') ?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <!-- BLOQUE 2 BUSQUEDA DE RETIRANTE ############################################################################################################## -->
        <div style='border: 1px solid #ccc; border-radius: 4px;padding:5px'>
            <div class="row" style='padding-left:10px; '>
                <!-- Linea de busqueda -->
                <div class="col-md-4">
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'documento_retira')
                            ->textInput([
                                'id' => 'input_dni_persona_retira',
                                'onkeyup' => 'ValidarIngresoDni(1);',
                            ])
                            ->label(
                                $model->isNewRecord
                                    ? 'Buscar Persona que Retira por Dni'
                                    : 'DNI Persona que Retira'
                            ) ?>
                        <span class="input-group-btn" style="padding-top:27px;">
                            <?= SiteController::actionGet_boton_buscar_x_documento(
                                'btn_dni_retira',
                                'Buscar Dni',
                                'datos_persona(1);'
                            ) ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-8" style="padding-top:30px;" id="txt_mensaje_retira"></div>
            </div>
            <!-- DATOS ALTA DE PERSONA QUE RETIRA  -->
            <!-- <div id="div_datos_persona_retira" style="display:none;"> -->
            <div id="div_datos_persona_retira">
                <div class="row" style='padding-left:10px; padding-top:10px;'>
                    <!-- Datos de persona -->
                    <div class="col-md-12">
                        <!-- Columna Datos -->
                        <div class="row">
                            <!-- -------------------------------------------- -->
                            <div class="col-md-4">
                                <!-- Apellido -->
                                <?= $form
                                    ->field($model, 'apellido_retira')
                                    ->textInput([
                                        'id' => 'input_apellido_retira',
                                    ])
                                    ->label('Apellido') ?>
                            </div>
                            <div class="col-md-8">
                                <!-- Nombres -->
                                <?= $form
                                    ->field($model, 'nombre_retira')
                                    ->textInput(['id' => 'input_nombre_retira'])
                                    ->label('Nombres') ?>
                            </div>
                        </div>

                        <div class="row">
                            <!-- -------------------------------------------- -->
                            <div class="col-md-2">
                                <!-- id persona -->
                                <?= $form
                                    ->field($model, 'persona_retira')
                                    ->textInput([
                                        'id' => 'input_idpersona_retira',
                                        'readonly' => true,
                                    ])
                                    ->label('Id Persona') ?>
                            </div>
                            <div class="col-md-4">
                                <!-- tipo documento -->
                                <?= Sds_com_configuracionController::actionGet_cmb_dropdown_configuracion(
                                    $form,
                                    $model,
                                    'documento_tipo_retira',
                                    'input_combo_tipo_documento_retira',
                                    'Tipo Documento',
                                    Sds_com_configuracion_tipo::TIPO_TIPO_DOC
                                ) ?>
                            </div>
                            <div class="col-md-3">
                                <!-- documento -->
                                <?= $form
                                    ->field($model, 'documento_retira')
                                    ->textInput([
                                        'id' => 'input_numero_documento_retira',
                                    ])
                                    ->label('Numero Documento') ?>
                            </div>
                        </div>

                        <div class="row">
                            <!-- -------------------------------------------- -->
                            <div class="col-md-4">
                                <!-- fecha de nacimiento -->
                                <?= SiteController::actionGet_input_fecha(
                                    $form,
                                    $model,
                                    'fecha_nacimiento_retira',
                                    'input_fecha_nacimiento_retira',
                                    'Fecha de Nacimiento',
                                    $generada,
                                    null
                                ) ?>
                            </div>
                            <div class="col-md-4">
                                <!-- Nacionalidad -->
                                <?= Sds_com_configuracionController::actionGet_cmb_dropdown_configuracion(
                                    $form,
                                    $model,
                                    'nacionalidad_retira',
                                    'input_combo_nacionalidad_retira',
                                    'Nacionalidad',
                                    Sds_com_configuracion_tipo::TIPO_NACIONALIDAD
                                ) ?>
                            </div>
                            <div class="col-md-4">
                                <!-- Genero -->
                                <?= Sds_com_configuracionController::actionGet_cmb_dropdown_configuracion(
                                    $form,
                                    $model,
                                    'genero_retira',
                                    'input_combo_genero_retira',
                                    'Genero',
                                    Sds_com_configuracion_tipo::TIPO_GENERO
                                ) ?>
                            </div>
                        </div>

                    </div>
                    <!-- <div class="col-md-3" style="text-align: center;">
                                    <img id="renaper_foto_retira" src="" alt="" height="200px" />
                            </div> -->
                </div>
                <div class="row" style='padding-left:10px; '>
                    <div class="col-md-5">
                        <!-- LOCALIDADES -->
                        <?= Sds_com_localidadController::actionGet_cmb_localidad(
                            $form,
                            $model,
                            'localidad_retira',
                            'combo_localidad_retira',
                            'Localidad',
                            58
                        ) ?>
                    </div>
                    <div class="col-md-5">
                        <!-- Calle -->
                        <?= $form
                            ->field($model, 'calle_retira')
                            ->textInput(['id' => 'input_calle_retira'])
                            ->label('Calle') ?>
                    </div>
                    <div class="col-md-2">
                        <!-- Numero -->
                        <?= $form
                            ->field($model, 'numero_calle_retira')
                            ->textInput(['id' => 'input_numero_calle_retira'])
                            ->label('Numero') ?>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'acta_original')->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'observaciones')
                    ->textarea(['rows' => 4]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'temp_archivo_adjunto_entrega', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::class, [
                        'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx', 'id' => 'control_temp_archivo_adjunto_entrega'],
                        'language' => 'es',

                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_stk_entrega/update']),
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialPreview' => [
                                Url::to('@web/uploads/entregas/' . $model->adjunto_acta_entrega, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                            ],
                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                            'initialPreviewFileType' => Sds_stk_entrega::getExtension($model->adjunto_acta_entrega), // image is the default and can be overridden in config below
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'initialCaption' => false,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                                "fileclear" => "alert();",
                            ]
                        ],
                    ])->label("Acta Adjunta");
                ?>
            </div>
        </div>



    </div>
    <!-- LINEA GRILLA DE ITEMS ############################################################################################################ -->
    <div class="row" style="border-radius: 5px; padding: 15px;<?= $model->isNewRecord || $generada
                                                                    ? 'display:none'
                                                                    : '' ?>">

        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;">

        </div>
    </div>



    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?php echo Html::button('Guardar Entrega', [
                'id' => 'btnGuardar',
                'class' => 'btn btn-primary',
                'type' => 'submit',
            ]);
            //Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
            ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>



<!-- DIV ITEMS ########################################################################################################################################## -->
<div class="row" id="abm_items" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Item
                </h3>
            </header>
            <div class="panel-body">
                <?php
                $model_entrega_item = new Sds_stk_entrega_item();
                echo $this->render('/sds_stk_entrega_item/_form', [
                    'model' => $model_entrega_item,
                    'identrega' => $model->identrega, // <----- aca le paso como parametro el id de la entrega
                    'botones' => true,
                ]);
                ?>
            </div>
        </section>
    </div>
</div>

<?php $this->registerJsFile('@web/js/stock.js'); ?>
<?php
$script = <<<JS



    refrescar_grilla();
    refrescar_destinatario();
    mostrar_organizacion_social();

    function validar()
    {
        let ban = 0;
        let txt_aux = '';


        if($('#check_organizacion_social').prop('checked')==true)
        {
            ban = $('#cmb_organizacion_social').val() > 0 ? ban : 1
            txt_aux = $('#cmb_organizacion_social').val() > 0 ? txt_aux : txt_aux + 'Debe seleccionar Organizacion social <br>';


            if ($('#hidden_input_adjunto_acta_entrega').val()=='')
            {
                if($('#control_temp_archivo_adjunto_entrega').val()=='')
                    {
                        ban = 1;
                        txt_aux = txt_aux + 'Debe adjuntar un Acta de entrega'
                    }
            }
            
        }
        //$.alert({typeAnimated: true,icon: 'fa fa-warning',title: 'Atención!',content: txt_aux,type: 'red',});
        ban == 0 ? $("#btnGuardarSubmit").click():$.alert({typeAnimated: true,icon: 'fa fa-warning',title: 'Atención!',content: txt_aux,type: 'red',});
    }

    function ValidarIngresoDni(tipo_persona) {
        var aux = event.which;

        if (aux == 13) //pregunto si fue el enter
        {
            datos_persona(tipo_persona);
        }
    }
    
    function refrescar_destinatario()
    {
        console.log('Ejecutando refrescar_destinatario');
        let dni_destinatario = $('#input_numero_documento_destinatario').val();
        console.log('dni_destinatario');
        console.log(dni_destinatario);
        if(dni_destinatario)
        {datos_persona(0);}
        console.log('termina  refrescar_destinatario');
    }



    function refrescar_retira()
    {
        let dni_retira = $('#input_numero_documento_retira').val();
        console.log('dni_retira');
        console.log(dni_retira);
        if(dni_retira)
        {datos_persona(1);} 
    }
    function mostrar_abm_entrega_item() {
        $("#abm_items").show();
        $('#id_formulario_entrega').hide();
        $("#btnGuardar").hide();
        $("#btnCerrar").hide();
    }

    function refrescar_grilla() {
        id_entrega = $('#hidden_input_id_entrega').val();
        if (id_entrega) {
            aux = "index.php?r=sds_stk_entrega_item/grilla_items&identrega=" + id_entrega;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
            });
        }
    }

    function eliminar_item(id_entrega_item) {
        $.post("index.php?r=sds_stk_entrega_item/delete&id=" + id_entrega_item, function(data) {
            if (data > 0) {
                refrescar_grilla();
                //alert('eliminado');
            } else {
                alert('no se ha eliminado')
            }
        });
    }

    //giladas de persona----------------------------------------------------------------
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

     function BloquearControlesPersona(option,tipo_persona) {
        console.log('BloquearControlesPersona');
        tipo_persona_string = tipo_persona==0 ? 'destinatario' : 'retira';
        if (option === true) {
            $('#input_combo_nacionalidad_' + tipo_persona_string).prop("readonly", true);
            $('#input_combo_genero_' + tipo_persona_string).prop("readonly", true);
            $('#input_combo_tipo_documento_' + tipo_persona_string).prop("readonly", true);
            $('#input_numero_documento_' + tipo_persona_string).prop("readonly", true);
            $('#input_fecha_nacimiento_' + tipo_persona_string).prop("readonly", true);
            $('#input_apellido_' + tipo_persona_string).prop("readonly", true);
            $('#input_nombre_' + tipo_persona_string).prop("readonly", true);
            $('#input_numero_calle_' + tipo_persona_string).prop("readonly", true);
            $('#input_calle_' + tipo_persona_string).prop("readonly", true);
            $('#combo_localidad_' + tipo_persona_string).prop("readonly", true);
        } else {
            $('#input_combo_nacionalidad_' + tipo_persona_string).prop("readonly", false);
            $('#input_combo_genero_' + tipo_persona_string).prop("readonly", false);
            $('#input_combo_tipo_documento_' + tipo_persona_string).prop("readonly", false);
            $('#input_numero_documento_' + tipo_persona_string).prop("readonly", false);
            $('#input_fecha_nacimiento_' + tipo_persona_string).prop("readonly", false);
            $('#input_apellido_' + tipo_persona_string).prop("readonly", false);
            $('#input_nombre_' + tipo_persona_string).prop("readonly", false);
            $('#input_numero_calle_' + tipo_persona_string).prop("readonly", false);
            $('#input_calle_' + tipo_persona_string).prop("readonly", false);
            $('#combo_localidad_' + tipo_persona_string).prop("readonly", false);
        }
    } 

     function buscar_foto_en_renaper(dni_persona,tipo_persona) {
        console.log('buscar_foto_en_renaper');
        console.log(tipo_persona);
        let tipo_persona_string = tipo_persona==0 ? 'destinatario' : 'retira';
        console.log(tipo_persona_string);
        //gif
            gif = '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uvXr2LNr3869u/fv4MOL/x9Pvrz58+jTq1/Pvr379/Djy5+vvMACAvRrGlhgoOCAAvmZRACABC1gIEEGMMBAgCQlYIABBxR4oEAFKLgAgyQ9aIACAxl4oUALKDgAhiMV8GCEIE44gIUkknTAgwR6CAACCjJAYIshEaBhAgDIeICC/Q2EAAEE8IjjRAgMMACHA71owIgFVSiAQAkoQCQBCBw5kQJKKpnljAbgp5AAVxIwpZYTEdDlf0YuVKYCbaIpUZJrNjQklnJixOUAZzL0ZZ4YJSAmoDmVSSSchCpUpaGDNsQoAUwmepCVjELEKKKSHrSooZne9Genczp056eg+kkkqQhRCmmcpWqqaqQLkdh5ZZ+tFiQrkbQeNORAm+JZK0F3rjpQkQbdSSsCVrJaq6C+CiRrllcKdKWyvzLErJjRznhotRFR+mW2AHjLrajbSkskr+COqxClbaYrq7oM4UpQujNSCy9BCZBqJaz3UlSlvf0GLPDABBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9daSBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn6+cgAEC9GseMHCg4AD8+ZWEgAIFGWAgQQcssECAJCUwwH8EGWjAQAQoOCGDIz04AAIDSdihggNgOJICGnZ4IAADWCgiSQU8SCAAHiag4AIADpTAih0hoOGNHqa4QH8DDcAAAwXgSFECBCjA4UAtQlhQAQsI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9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dY5BQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgoAAE+ZmUAIAEEWBggQUUECBJCRgoQIEHCoRAggQsSJKBBOAnEIYDEZDggxaKhICB/W0YoQAJKhjiSPsRQCCHCaRI4EAarrhRgwbixyGKBVQ4kAIDDDCjjRE1qMCQAhg4pIQZShhkkCUSGdGIJGrY4JIFxfjkACpKKVGSGApQo0JbCullRfZh6ONCAkB5JkZUYolQk29iNGCdOxmg554HrInnQWmq6SdDexZqwJ8JtSgoRIYa0CeiCAWqJqQ3DTAmpRDJeVABCywwAKaZKtlQp50aMCioBkkapUIIHEDqAgeg4YoQmA4uNAADIBJgAKmfykoQlfzVSCdBBzDAwKEDDbDrqajiiKSoMBrLQJe+PoSjjxwCcCsDC1QbUYsvRgjAAsb26i1DVEaZLQAESHtuQy0KKy4ABhh76bsG1drhvNpSiy+gS+636r8U2XcvwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIctNmUBAQAh+QQJCgAAACwAAAAALAHlAAAI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3K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bPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dbrBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgIAL8+ZVUwAAFEWAgQQIcGOBICTDAgAEEGUjAQAkY2N+CIznIwIQCSTjQfgQggOFIAzi4wEAeAoCAhSOStICDBALgYYUGAkhhix0VoKGIHq5IgAAE+WgjjhERsIABMQpkgIMHGFQhgPZJKCKREg2wwJUGcCgAA0kelECCEgJJ5UQHXHnlAVMuJCF/Q45ZpAFmnsiQj2m6WZGVCxTg0H92ZpRAl33etKaFbQZa4AEGJKooRIOyaChCikYK4UON3vcoQgQgKumlN/HJqUQV7mlgnZ8ypMAAA4i5EIhslroQAqjG39rQl2uq6mpBA8ZaQKFBEgAlq6TeeiqqBYj5pEE+2orAfryWCusAFwoEJo8H0ujrrbMWZC1+KfoYLbYPgThligCIC+5D3qKoIADWnusQiDaSCwCYzbob4Y/3cjiQp/YqlACp+33bb0X21TvwwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIfdXkAAOw==';

        // jpg 
        user_picture = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxIHEhASEBMOEBAWEBUQEBARFRIPFhIQFREXFhUVExMYHSggGRolGxUVITEhJSkrLi4uFx8/ODMsNygtLisBCgoKDQ0NDg0NDisZFRkrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrK//AABEIAOEA4QMBIgACEQEDEQH/xAAbAAEAAgMBAQAAAAAAAAAAAAAAAgUDBAYBB//EADQQAAIBAQUFBgUEAwEAAAAAAAABAgMEBRESMSFBUXGBEzJhkaHBFCJysdFCUuHwM2Kygv/EABUBAQEAAAAAAAAAAAAAAAAAAAAB/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8A+1gAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACUY4nuQUyYEMgyEwBDIMhMAQyDIa9rvCFl2N4y/atr68CmtN71K3d+ReGvn+AL6rOFHvSUebSNGre1KGmeXJYfcoJPNteLfF7TwC3nfXCHnL+DG75n+2Hr+SsBUWavmf7Yev5JwvrjDylh7FSAL+le9KWuePNYr0N2jWhX7sk/BPb5HJnuhFdhkGQ5yzXpUob864S2+upcWO9IWnZ3JcHv5MDbyDITAEMgyEwBDIeSjgZCM9AMYAAAAAAAJ0yZCmTAAGOvWjZ4uUngl/cEBKpNU03JpJatlFb73dXGNPGMf3b3y4Grb7dK2Pbsiu7H3fFmoUAAEAAAAAAAAAAAAAFjYL1lZ8FLGcPVcn7F/QrRrpSi8V/dTjzPZLVKySxj1W5rxCutBgsdqja45o9VvT8TOQCM9CRGegGMAAAAAAAE6ZMhTJgRqTVNNt4JLFs5i8La7ZLHSK7sfd+JtX3be1fZxfyp/N4y4dCqAAAqAAAAAAAAAAAAAAAAAAAz2S0ysklKPVcVwOps9dWiKlHR+j4M4837ptvwssH3Ja+D3MDpSM9CRGehFYwAAAAAAATpmvedq+Fg2u8/ljz49DYpnPX3aO2qYLSPy9d/46AV4AKgAAAAAAGezWWVp02LfJ6AYAXdG74U9VmfF/g2YxUNElyWAVzYOlks2uD57TWrWCFXdlfGOz00AowbVqsUrPt1jxXutxqhAAAAAAAAHR3Lau3hlfejs5x3fg356HL3baPhqkXufyy5P+p9DqJ6EVjAAAAAAAAqVexhKT3Jv0OSbzYt6va+Zf3zUyUsP3SS6Lb7HPgAAVAAAAABs2Ky/Ev/Vav2RdwioJJLBLRGOy0ewilv1fPeZgoAAAAA8axKe8LJ2DzR7r9Hw5FyQq01VTi9GsAOcBKcHTbT1TwZEIAAAAAB1NgrdvSi9+GD5rYcsXdwVMY1I8Gpeez2AswARQAAAABUX/AC/xr6n9ioLO/X88fp92VhUAAAAAAz2KGepBeOPlt9jAbV2/5I9f+WBeAAKAAAAAAAApb0hlqPxSft7Gmb97v519PuzQCAAAAAAWVxSwnJcYP0aK03bneFWPJ/YDoQARQAAAABSX6vnj9HuysLa/o/439S+xUlQAAAAADJZ6nZSjLg8em8xgDpgaV2WjtY5X3o7Oa3M3QoAAAAAAGvbbR8PHH9T2R58QKq8KnaVJcF8q6f1msAEAAAAAA3boWNWPJ/8ALNIsbjjjUb4Qf3SAvQARQAAAABoX3TzUsf2zT6PZ+CgOsr0u3hOPFNLnhs9TlNAPAAVAAAAABOlUdJprY0XdktcbSuEt8fxxRQnqeGmwDpQU9G8pw72El5PzNmN6QeqkvJhW+DRlekFopvyXua1a9JS7qUfHVgWNotEbOsZdFvZSWiu7Q8X0XBEJyc3i22+LIhAAAAAAAAAubgp7KkvFRXTa/uimOnuyj2NKPF/M+u37YAZwARQAAAABOmc5e9n7Co+EvmXXX1+50dM1L3svxMNnej8y8eK/vADmQAVAAAACUYubwSbfBARBZWe629s3h/qterN+lZoUe7FLx1fmwqjhQnU0jJ9GZVYKj/T6r8l6CCidgqL9PrH8mKdmnDWMvLE6IAcwDo6tCNXvRT8d/maFe698H/5fsyirBOpTdJ4STT8SAQAAAAAZ7DQ+JnGO7HF/StTq57EVlxWXs4ub1lp9P8/gs56EVjAAAAAAABOmTIUyYHOXxY/h5Zl3JPylvRXHYV6KrxcZbU/7ijlrZZXZJOL6PiijAAZbPRdokorq+C4hHtms0rS8Fpve5F3ZrNGzrCOu9vVkqNJUEox0+74syEUAAAAAAAAAAGOtRjXWElj91yZS2yyOzPjHc/Z+JfEZwVRNNYp6oDmgbFsszs0sNU9sX4fk1yoG3dtk+Lnh+lbZPw4dTBQou0SUYrFv08X4HU2OyqyRUVzb4viBmSw2LQ8noSIz0IrGAAAAAAACdMmQpkwBgtllja45ZdHvT4ozgDkrXZZWSWEuj3NeBa3dZ+wji+89r8FuRaVqMa6wkk1rt4mGpScPFAQAAAAAAAAAAAAAAABgtdD4iLW/WL4MpKNCVaWWKxl9ufA6SFNz5cTPRoRo45Uk28ZPiwMF32FWNcZPvS9l4G2AAIz0JEZ6AYwAAAAAAATpkzHCWBLOgJAjnQzoCQI50M6AhOipabDDKm4mznQzoDUBsyUZGN0luYGIE3T5HmRgRBLIz1U+QEAZVSW9k4xjH+QMEYOWhnhRw12k8yGdASBHOhnQEgRzoZ0BIjPQZ0eSliBAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB//Z';
       
        /* $('#renaper_foto_' + tipo_persona_string).attr("width", "160px");
        $('#renaper_foto_' + tipo_persona_string).attr("src", 'data:image/gif;base64,' + gif); */

        if(tipo_persona==0)
            {
                /* $('#renaper_foto_retira').attr("width", "160px");
                $('#renaper_foto_retira').attr("src", 'data:image/gif;base64,' + gif); */
            }
       /*  $.ajax({
            data: {
                'servicio': '*get_renaper',
                'auditoria': 'motu',
                'usuario_auditoria': 'motu',
                'filtro': 'documento=' + dni_persona,
                'tipo': 0
            },
            type: "POST",
            dataType: "json",
            url: "https://apisur.neuquen.gov.ar/index.php",
            success: function(data) {
                $.each(data, function(ind, elem) {
                    //respuesta = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                    console.log(ind);
                    if (ind == 'status') {
                        elem_aux = elem;
                        console.log(elem_aux);
                        if (elem_aux == 'error') {
                            
                            console.log('ERRRRRRRRRRROOOOORRRRRRRRRRRRR');
                            console.log(tipo_persona_string);
                            console.log('#renaper_foto_' + tipo_persona_string);
                            $('#renaper_foto_' + tipo_persona_string).attr("width", "");
                            $('#renaper_foto_' + tipo_persona_string).attr("src", user_picture);
                            if(tipo_persona==0)
                                {
                                    $('#renaper_foto_retira').attr("width", "");
                                    $('#renaper_foto_retira').attr("src", user_picture);
                                }
                            return;
                        }
                    }

                    if (ind == 'records') {
                        console.log(elem[0]);
                        completar_direccion(dni_persona,tipo_persona,elem[0].result.calle,elem[0].result.numero,elem[0].result.cpostal);
                        $('#renaper_foto_' + tipo_persona_string).attr("src", elem[0].result.foto);
                        if(tipo_persona==0)
                        {
                            $('#renaper_foto_destinatario').attr("src", elem[0].result.foto);
                            $('#renaper_foto_retira').attr("src", elem[0].result.foto);
                        }
                        
                    }
                    
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                
                console.log(xhr.status);
                console.log(thrownError);
            },

        }); */
    } 

     function completar_direccion(dni_persona,tipo_persona, calle, numero, cpostal)
        {
            console.log('completar_direccion->' + cpostal);
            let codigo_postal = cpostal;
            let tipo_persona_string = tipo_persona==0 ? 'destinatario' : 'retira';
            if($('#input_numero_calle_' + tipo_persona_string).val()=='')
                {
                    $('#input_numero_calle_' + tipo_persona_string).val(numero);
                    $('#input_numero_calle_' + tipo_persona_string).prop("readonly", true);
                }

            if($('#input_calle_' + tipo_persona_string).val()=='')
                {
                    $('#input_calle_' + tipo_persona_string).val(calle);
                    $('#input_calle_' + tipo_persona_string).prop("readonly", true);
                }

            if($('#combo_localidad_' + tipo_persona_string).val()==0)
            {
                console.log('post a localidades en completar_direccion');
                $.post("index.php?r=sds_com_localidad/get_id_localidad_por_codigo_postal&codigo_postal=" + codigo_postal, function(data) {

                $('#combo_localidad_' + tipo_persona_string).val(data).trigger("change");; 
                $('#combo_localidad_' + tipo_persona_string).prop("readonly", true);   

                }); 
                if(tipo_persona==0)
                            {
                                clonar_datos_destinatario(dni_persona);
                            }
            }
        } 

     function LimpiarCamposAltaPersona(dni_persona,tipo_persona) {
        console.log('LimpiarCamposAltaPersona');
        tipo_persona_string = tipo_persona==0 ? 'destinatario' : 'retira';
        /* $('#renaper_foto_' + tipo_persona_string).attr("src", ""); */
        $('#txt_mensaje_destinatario_alta_persona').html("");
        $('#input_combo_nacionalidad_' + tipo_persona_string).val("");
        $('#input_combo_genero_' + tipo_persona_string).val("");
        $('#input_apellido_' + tipo_persona_string).val("");
        $('#input_nombre_' + tipo_persona_string).val("");
        $('#input_combo_tipo_documento_' + tipo_persona_string).val("");
        $('#input_numero_documento_' + tipo_persona_string).val(dni_persona);
        $('#input_fecha_nacimiento_' + tipo_persona_string).val("");
        $('#input_calle_' + tipo_persona_string).val("");
        $('#input_numero_calle_' + tipo_persona_string).val("");
        $('#combo_localidad_' + tipo_persona_string).val("");
    } 

     function PrepararCamposAltaPersonaAMano(dni_persona,tipo_persona) {
        console.log('PrepararCamposAltaPersonaAMano');
        tipo_persona_string = tipo_persona==0 ? 'destinatario' : 'retira';
        // jpg 
            user_picture = 'data:image/jpeg;base64,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';
        $('#txt_mensaje_' + tipo_persona_string).html("<p style='color: red;'>Persona no encontrada, complete los datos a mano</p>");
        /* $('#renaper_foto_' + tipo_persona_string).attr("width", "160px");
        $('#renaper_foto_' + tipo_persona_string).attr("src", user_picture); */
        $('#txt_mensaje_destinatario_alta_persona').html("");
        $('#input_combo_nacionalidad_' + tipo_persona_string).val("");
        $('#input_combo_genero_' + tipo_persona_string).val("");
        $('#input_apellido_' + tipo_persona_string).val("");
        $('#input_nombre_' + tipo_persona_string).val("");
        $('#input_combo_tipo_documento_' + tipo_persona_string).val("");
        $('#input_numero_documento_' + tipo_persona_string).val(dni_persona);
        $('#input_fecha_nacimiento_' + tipo_persona_string).val("");
        $('#input_calle_' + tipo_persona_string).val("");
        $('#input_numero_calle_' + tipo_persona_string).val("");
        $('#combo_localidad_' + tipo_persona_string).val("");
        $("#loading").hide();


    } 

     function buscar_en_renaper(dni_persona,tipo_persona) {
        console.log('buscar_en_renaper');
        $("#loading").show();
        tipo_persona_string = tipo_persona==0 ? 'destinatario' : 'retira';
         
        //gif
            gif = 'R0lGODlhLAHlAPcAAP///    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x9Pvrz58+jTq1/Pvr379/Djy5+vvMACAvRrGlhgoOCAAvmZRACABC1gIEEGMMBAgCQlYIABBxR4oEAFKLgAgyQ9aIACAxl4oUALKDgAhiMV8GCEIE44gIUkknTAgwR6CAACCjJAYIshEaBhAgDIeICC/Q2EAAEE8IjjRAgMMACHA71owIgFVSiAQAkoQCQBCBw5kQJKKpnljAbgp5AAVxIwpZYTEdDlf0YuVKYCbaIpUZJrNjQklnJixOUAZzL0ZZ4YJSAmoDmVSSSchCpUpaGDNsQoAUwmepCVjELEKKKSHrSooZne9Genczp056eg+kkkqQhRCmmcpWqqaqQLkdh5ZZ+tFiQrkbQeNORAm+JZK0F3rjpQkQbdSSsCVrJaq6C+CiRrllcKdKWyvzLErJjRznhotRFR+mW2AHjLrajbSkskr+COqxClbaYrq7oM4UpQujNSCy9BCZBqJaz3UlSlvf0GLPDABBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9daSBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn6+cgAEC9GseMHCg4AD8+ZWEgAIFGWAgQQcssECAJCUwwH8EGWjAQAQoOCGDIz04AAIDSdihggNgOJICGnZ4IAADWCgiSQU8SCAAHiag4AIADpTAih0hoOGNHqa4QH8DDcAAAwXgSFECBCjA4UAtQlhQAQsIIBCUQzIQopESIUDAlgrcC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NNQRy311FRXbfXVWGet9dY5BQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgoAAE+ZmUAIAEEWBggQUUECBJCRgoQIEHCoRAggQsSJKBBOAnEIYDEZDggxaKhICB/W0YoQAJKhjiSPsRQCCHCaRI4EAarrhRgwbixyGKBVQ4kAIDDDCjjRE1qMCQAhg4pIQZShhkkCUSGdGIJGrY4JIFxfjkACpKKVGSGApQo0JbCullRfZh6ONCAkB5JkZUYolQk29iNGCdOxmg554HrInnQWmq6SdDexZqwJ8JtSgoRIYa0CeiCAWqJqQ3DTAmpRDJeVABCywwAKaZKtlQp50aMCioBkkapUIIHEDqAgeg4YoQmA4uNAADIBJgAKmfykoQlfzVSCdBBzDAwKEDDbDrqajiiKSoMBrLQJe+PoSjjxwCcCsDC1QbUYsvRgjAAsb26i1DVEaZLQAESHtuQy0KKy4ABhh76bsG1drhvNpSiy+gS+636r8U2XcvwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIctNmUBAQAh+QQJCgAAACwAAAAALAHlAAAI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3KtKnTp1CjSp1KtarVq1iza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PPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dbrBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgIAL8+ZVUwAAFEWAgQQIcGOBICTDAgAEEGUjAQAkY2N+CIznIwIQCSTjQfgQggOFIAzi4wEAeAoCAhSOStICDBALgYYUGAkhhix0VoKGIHq5IgAAE+WgjjhERsIABMQpkgIMHGFQhgPZJKCKREg2wwJUGcCgAA0kelECCEgJJ5UQHXHnlAVMuJCF/Q45ZpAFmnsiQj2m6WZGVCxTg0H92ZpRAl33etKaFbQZa4AEGJKooRIOyaChCikYK4UON3vcoQgQgKumlN/HJqUQV7mlgnZ8ypMAAA4i5EIhslroQAqjG39rQl2uq6mpBA8ZaQKFBEgAlq6TeeiqqBYj5pEE+2orAfryWCusAFwoEJo8H0ujrrbMWZC1+KfoYLbYPgThligCIC+5D3qKoIADWnusQiDaSCwCYzbob4Y/3cjiQp/YqlACp+33bb0X21TvwwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIfdXkAAOw==';
        /* $('#renaper_foto_' + tipo_persona_string).attr("width", "160px");
        $('#renaper_foto_' + tipo_persona_string).attr("src", 'data:image/gif;base64,' + gif); */

        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni_persona, function(data) {
            $.each(data, function(ind, elem) {
                //respuesta = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                console.log(ind);
                if (ind == 'status') {
                    elem_aux = elem;
                    console.log(elem_aux);
                    if (elem_aux == 'error') {
                        $('#txt_mensaje_' + tipo_persona_string).html("No se encontro informacion del dni " + dni_persona + " , complete el alta manualmente");

                        $('#input_fecha_nacimiento_' + tipo_persona_string).prop("readonly", false);
                        $('#input_apellido_' + tipo_persona_string).prop("readonly", false);
                        $('#input_nombre_' + tipo_persona_string).prop("readonly", false);
                        /* $('#renaper_foto_' + tipo_persona_string).attr("width", "");
                        $('#renaper_foto_' + tipo_persona_string).attr("src", ""); */
                        $("#loading").hide();
                        PrepararCamposAltaPersonaAMano(dni_persona,tipo_persona);
                        return;
                    }
                }

                if (ind == 'records') {
                    console.log(elem[0]);

                    $('#input_apellido_' + tipo_persona_string).val(corregir_palabra(elem[0].result.apellido));
                    $('#input_nombre_' + tipo_persona_string).val(corregir_palabra(elem[0].result.nombres));
                    $('#input_fecha_nacimiento_' + tipo_persona_string).val(elem[0].result.fecha_nacimiento);
                    /* $('#renaper_foto_' + tipo_persona_string).attr("src", elem[0].result.foto); */
                    $('#input_fecha_nacimiento_' + tipo_persona_string).prop("readonly", true);
                    $('#input_apellido_' + tipo_persona_string).prop("readonly", true);
                    $('#input_nombre_' + tipo_persona_string).prop("readonly", true);

                    $('#input_numero_calle_' + tipo_persona_string).val(elem[0].result.numero);
                    $('#input_numero_calle_' + tipo_persona_string).prop("readonly", true);
                    
                    $('#input_calle_' + tipo_persona_string).val(corregir_palabra(elem[0].result.calle));
                    $('#input_calle_' + tipo_persona_string).prop("readonly", true);
                    console.log('post a localidades en buscar_en_renaper');
                    $.post("index.php?r=sds_com_localidad/get_id_localidad_por_codigo_postal&codigo_postal=" + elem[0].result.cpostal, function(data) {
                        console.log('post a localidades:');
                        console.log(data);

                        $('#combo_localidad_' + tipo_persona_string).val(data).trigger("change"); 
                        $('#combo_localidad_' + tipo_persona_string).prop("readonly", true);   

                        });


                    $('#txt_mensaje_' + tipo_persona_string).html("Persona encontrada en RENAPER, completar datos faltantes para el alta...");
                    $("#loading").hide();
                    if(tipo_persona==0){
                        clonar_datos_destinatario(dni_persona);
                        /* $('#renaper_foto_destinatario').attr("src", elem[0].result.foto);
                        $('#renaper_foto_retira').attr("src", elem[0].result.foto); */
                    }
                }
            });           
        });
    } 

     function clonar_datos_destinatario(dni_persona)
        {
            console.log('clonar_datos_destinatario');
            $("#input_dni_persona_retira").val(dni_persona);
            $('#input_idpersona_retira').val($('#input_idpersona_destinatario').val());
            $('#input_combo_nacionalidad_retira').val($('#input_combo_nacionalidad_destinatario').val());
            $('#input_combo_genero_retira').val($('#input_combo_genero_destinatario').val());
            $('#input_apellido_retira').val($('#input_apellido_destinatario').val());
            $('#input_nombre_retira').val($('#input_nombre_destinatario').val());
            $('#input_combo_tipo_documento_retira').val($('#input_combo_tipo_documento_destinatario').val());
            $('#input_numero_documento_retira').val(dni_persona);
            $('#input_fecha_nacimiento_retira').val($('#input_fecha_nacimiento_destinatario').val());
            
            $('#input_calle_retira').val($('#input_calle_destinatario').val());
            $('#input_numero_calle_retira').val($('#input_numero_calle_destinatario').val());
            $('#combo_localidad_retira').val($('#combo_localidad_destinatario').val()).trigger("change"); 

            $('#txt_mensaje_retira').html($('#txt_mensaje_destinatario').html())
        }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        palabra = palabra.toLowerCase();
        palabra = palabra.replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase());
        return palabra;
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }  

    function mostrar_organizacion_social(){
        $('#check_organizacion_social').prop('checked')==true ? $('#div_organizacion_social').show(1000) : $('#div_organizacion_social').hide(1000);
    }




JS;
$this->registerJs($script);
?>