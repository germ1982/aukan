<?php

use app\models\Mds_cap_instancia;
use app\models\Mds_cap_docente;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_docente */
/* @var $form yii\widgets\ActiveForm */

function botonAltaConfiguracion($model)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to([
            '//sds_com_configuracion/create_ext',
            'tipo' => Sds_com_configuracion_tipo::CAP_DOCENTE_PROFCORTA,
        ]),
        'class' => 'btn btn-success btn-flat',
        'id' =>
            'btn_config_' . Sds_com_configuracion_tipo::CAP_DOCENTE_PROFCORTA,
        'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        // "disabled" => !$model->isNewRecord,
        'onclick' => '
            $("#abm_configuracion").show();
            $("#abm_configuracion_content").load($(this).attr("value"));
            $("#abm_configuracion_title").html("Agregar Profesión Corta");
            $("#btnGuardar").hide();$("#btnCerrar").hide();
            $("#docente_form").hide();',
    ]);
}
?>
<!--
<script>
    $('#nombre_docente').prop("disabled", true);
    $('#apellido_docente').prop("disabled", true);
    $('#datos_docente').prop("disabled", true);
    $('#firma').prop("disabled", true);
    $('#firma_digital').prop("disabled", true);
    $('#profesion_corta').prop("disabled", true);
    $('#cargo_certificado').prop("disabled", true);
    $('#email').prop("disabled", true);
    $('#telefono').prop("disabled", true);
    $('#localidad').prop("disabled", true);
</script> -->

<div class="mds-cap-docente-form" id="docente_form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <!-- ###################### -->
        <div class="col-md-4">
            <!-- busca el docente por DNI -->
            <?= $form->field($model, 'dni')->textInput([
                'id' => 'txtDNI',
                'disabled' => !$model->isNewRecord,
            ]) ?>
        </div>

        <div class="col-md-2" style="padding-top:25px;">
            <?php echo Html::a(
                '<i class="glyphicon glyphicon-search"></i>',
                null,
                [
                    'name' => 'btn_dni_benef',
                    'id' => 'btn_dni_benef',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-primary',
                    'title' => Yii::t('app', 'Consultar DNI'),
                    'disabled' => !$model->isNewRecord,
                ]
            ); ?>
        </div>

        <div class="col-md-4" style="padding-top:30px;" id="txt_mensaje">
        </div>
    </div>

    <div class="row">

        <!-- ###################### -->
        <div class="col-md-4">
            <?= $form
                ->field($model, 'nombre')
                ->textInput(['id' => 'nombre_persona']) ?>
        </div>

        <div class="col-md-4">
            <?= $form
                ->field($model, 'apellido')
                ->textInput(['id' => 'apellido_persona']) ?>
        </div>

        <div class="col-md-4">
            <?php
            if ($model->fecha_nacimiento != null) {
                $model->fecha_nacimiento = date(
                    'd/m/Y',
                    strtotime(str_replace('/', '-', $model->fecha_nacimiento))
                );
            }
            echo $form
                ->field($model, 'fecha_nacimiento')
                ->widget(DatePicker::ClassName(), [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'fecha_nacimiento',
                        'class' => 'form-control input-md',
                        'disabled' => false,
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'endDate' => date('d/m/Y'),
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ],
                ])
                ->label('Fecha de Nacimiento');
            ?>
        </div>
        <?= $form
            ->field($model, 'idpersona')
            ->hiddenInput(['id' => 'hidden_nueva_persona'])
            ->label(false) ?>

    </div>

    <div class="row">
        <!-- ###################### -->
        <div class="col-md-4">
            <?= $form
                ->field($model, 'sexo')
                ->dropdownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguracionesActivas(
                            Sds_com_configuracion_tipo::TIPO_GENERO,
                            false
                        ),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    [
                        'id' => 'sexo_persona',
                        'prompt' => 'Seleccionar Genero ...',
                        'tabindex' => '1',
                    ]
                ) ?>
        </div>

        <div class="col-md-4">
            <?= $form
                ->field($model, 'nacionalidad')
                ->dropdownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguracionesActivas(
                            Sds_com_configuracion_tipo::TIPO_NACIONALIDAD,
                            false
                        ),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    [
                        'id' => 'nacionalidad_persona',
                        'prompt' => 'Seleccionar Nacionalidad ...',
                        'tabindex' => '1',
                    ]
                ) ?>
        </div>

        <div class="col-md-4">
            <?= $form
                ->field($model, 'localidad')
                ->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Sds_com_localidad::getLocalidadesMostrar(),
                        'idlocalidad',
                        'descripcion'
                    ),
                    'options' => [
                        'placeholder' => 'Seleccionar Localidad ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>
        </div>
    </div>


    <div class="row">
        <!-- ###################### -->
        <div class="col-md-8">
            <?= $form
                ->field($model, 'email')
                ->textInput(['id' => 'email', 'maxlength' => true])
                ->label('E-mail') ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'telefono')
                ->textInput(['id' => 'telefono']) ?>
        </div>
    </div>

    <div class="row">
        <!-- ###################### -->
        <div class="col-md-8">
            <?= $form
                ->field($model, 'datos_docente')
                ->textarea(['rows' => 3]) ?>
        </div>

        <div class="col-md-4">
            <div class="input-group">
                <?= $form
                    ->field($model, 'profesion_corta')
                    ->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguracionesActivas(
                                Sds_com_configuracion_tipo::CAP_DOCENTE_PROFCORTA,
                                true
                            ),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => '-- Seleccione profesiòn--',
                            'id' =>
                                'config_' .
                                Sds_com_configuracion_tipo::CAP_DOCENTE_PROFCORTA,
                        ]
                    )
                    ->label('Profesión Corta') ?>

                <span class="input-group-btn">
                    <?= botonAltaConfiguracion($model) ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- ###################### -->
        <div class="col-md-3">
            <?= $form->field($model, 'firma_digital')->dropDownList(
                [
                    Mds_cap_docente::FIRMA_DIGITAL_SI => 'Si',
                    Mds_cap_docente::FIRMA_DIGITAL_NO => 'No',
                ],
                ['prompt' => 'Posee firma digital?']
            ) ?>
        </div>

        <div class="col-md-5">
            <?= $form
                ->field($model, 'cargo_certificado')
                ->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class='col-md-8'>
            <?php if ($model->firma == null) {
                echo $form
                    ->field($model, 'temp_imagen', [
                        'enableClientValidation' => true,
                        'enableAjaxValidation' => false,
                    ])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => [
                                'jpg',
                                'jpeg',
                                'gif',
                                'png',
                                'bmp',
                            ],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to([
                                '/mds_cap_instancia/update',
                            ]),
                            'maxFileSize' => 100000,
                            'previewFileType' => 'file',
                            'initialCaption' => $model->firma,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ],
                        ],
                    ]);
            } else {
                echo $form
                    ->field($model, 'temp_imagen', [
                        'enableClientValidation' => true,
                        'enableAjaxValidation' => false,
                    ])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => [
                                'jpg',
                                'jpeg',
                                'gif',
                                'png',
                                'bmp',
                            ],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to([
                                '/mds_cap_instancia/update',
                            ]),
                            'maxFileSize' => 100000,
                            'previewFileType' => 'file',
                            'initialPreview' => [
                                Html::img(
                                    'uploads/instancias/firmas/' .
                                        $model->firma,
                                    [
                                        'class' => 'file-preview-image',
                                        'style' =>
                                            'width:100%; text-align: center',
                                    ]
                                ),
                            ],
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'initialCaption' => $model->firma,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                            ],
                        ],
                        'pluginEvents' => [
                            'fileclear' => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ 
                                $('#borrar_firma').val(true);alert( $('#borrar_firma').val());
                            }",
                            'filereset' => 'function() {  }',
                        ],
                    ]);
            } ?>
            <?= $form
                ->field($model, 'borrar_firma')
                ->hiddenInput(['id' => 'borrar_firma'])
                ->label(false) ?>
        </div>
    </div>
</div>
<div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 id="abm_configuracion_title" class="panel-title">
                </h3>
            </header>
            <div class="panel-body" id="abm_configuracion_content">
            </div>
        </section>
    </div>
</div>

<?php if (!Yii::$app->request->isAjax) { ?>
    <div class="form-group text-rigth">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', [
            'class' => $model->isNewRecord
                ? 'btn btn-success'
                : 'btn btn-primary',
        ]) ?>
    </div>
<?php } ?>

<?php ActiveForm::end(); ?>



<?php $this->registerJs(
    "
        $(document).ready(function(){
            if ($('#txtDNI').val()!='')
            {IniciarBusqueda();}
            
        }); 
            $('#btn_dni_benef').on('click',function(){IniciarBusqueda()});
            $('#txtDNI').keyup(function(e){ValidaringresoDni()});
            "
); ?>

<script>
    function ValidaringresoDni() {
        var aux = event.which;
        if (aux == 13) //pregunto si fue el enter
        {
            IniciarBusqueda();
        }
    }

    function IniciarBusqueda() {
        var dni_persona = $('#txtDNI').val();
        var id_persona = 0;
        $('#txt_mensaje').html("Buscando datos de Persona...");
        if (dni_persona == "") {
            alert("Escriba un dni");
            return;
        }
        $.post("consultas/sds_vio_intervencion_get_com_persona.php", {
                'dni_persona': dni_persona,
            },
            function(data) {
                id_persona = data['idpersona'];
                if (id_persona > 1) {
                    mostrar_datos_db(id_persona);
                } else {
                    mostrar_datos_renaper(dni_persona);
                }
            }, "json"
        );
    }


    function mostrar_datos_db(id_persona) {
        $.post("consultas/get_com_persona_segun_sistema.php", {
                'id_persona': id_persona,
                'tabla_sistema': 'sds_com_persona', //'mds_cap_persona',
            },
            function(data) {
                $("#hidden_nueva_persona").val(id_persona);
                $("#nombre_persona").val(data['nombre']);
                $("#apellido_persona").val(data['apellido']);
                $("#nacionalidad_persona").val(data['nacionalidad']);
                $("#sexo_persona").val(data['genero']);
                $("#fecha_nacimiento").val(FormatearFecha(data['fecha_nacimiento']));
                $('#txt_mensaje').html("");
            }, "json"
        );
    }

    function mostrar_datos_renaper(dni_campo) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni_campo, function(data) {
            if (data.status == "error") {
                $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos();
            } else {
                var nombre = "";
                var apellido = "";
                var domicilio = "";
                var localidad = "";
                var fecha_nacimiento = "";
                var sexo = "";
                var nacionalidad = "";
                var foto = "";
                $.each(data, function(ind, elem) {
                    //nacionalidad = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                    //$("#detalle").val(nacionalidad);//lo plante aca porque era un texto largo
                    console.log(ind);
                    if (ind == 'records') {
                        nombre = corregir_palabra(elem[0].result.nombres);
                        apellido = corregir_palabra(elem[0].result.apellido);
                        domicilio = corregir_palabra(elem[0].result.calle + " " + elem[0].result.numero);
                        localidad = corregir_palabra(elem[0].result.ciudad);
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                        //foto = elem[0].result.foto;
                    }
                });
                $("#hidden_nueva_persona").val('0');
                nombre = corregir_palabra(nombre);
                $("#nombre_persona").val(nombre);
                apellido = corregir_palabra(apellido);
                $("#apellido_persona").val(apellido);
                $("#fecha_nacimiento").val(fecha_nacimiento);
                $("#nacionalidad_persona").val(70);
                $("#sexo_persona").val(sexo ? 81 : 82);
                /* $("#renaper_foto").attr("src", foto); */
                $('#txt_mensaje').html("");
                habilitar_controles();
            }
        });
    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#nombre_persona").val('');
        $("#apellido_persona").val('');
        $("#fecha_nacimiento").val('');
        $("#nacionalidad_persona").val('');
        $("#sexo_persona").val('');
        $('#telefono_persona').val('');
        $('#mail_persona').val('');
        $("#hidden_nueva_persona").val(0);
    }

    function FormatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    } 

    function habilitar_controles() {
        $('#nombre_persona').prop("disabled", false);
        $('#apellido_persona').prop("disabled", false);
        $('#fecha_nacimiento').prop("disabled", false);
        $('#nacionalidad_persona').prop("disabled", false);
        $('#sexo_persona').prop("disabled", false);
        $('#telefono_persona').prop("disabled", false);
        $('#mail_persona').prop("disabled", false);
    }
</script>