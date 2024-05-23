<?php

use \bizley\quill\Quill;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\bootstrap\Modal;

?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<div class="mds-renovacion-form">

    <header class="page-header">
        <h2><?= $this->title ?></h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.php">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span><?= $model->isNewRecord ? $this->title : $titleright ?></span></li>
            </ol>
            <div class="sidebar-right-toggle"></div>
        </div>
    </header>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <div class="panel-group" id="accordion_rendicion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_rendicion" href="#rendicion">
                                        Datos de Rendición
                                    </a>
                                </h4>
                            </div>
                            <div id="rendicion" class="accordion-body collapse in">
                                <div class="panel-body" id="rendicion_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?=
                                            $form->field($model, 'idtipo')->dropdownList(
                                                $listTipoRendicion,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione',
                                                        'options' => [
                                                            'disabled' => true,
                                                            'selected' => true
                                                        ]
                                                    ],
                                                    'id' => 'tipo',
                                                    'onChange' => 'changeTipo()',
                                                    'disabled' => $model->isNewRecord ? false : true
                                                ]
                                            );
                                            ?>
                                        </div>
                                    </div>
                                    <div id="rendicion_div" style="display: none">
                                        <div class="row" id="persona_div">
                                            <div class="col-md-2 form-group">
                                                Tipo de documento
                                                <div style="padding-top:6px;">
                                                    <?=
                                                    Html::dropdownList(
                                                        'ListaTiposDocumento',
                                                        '',
                                                        $tiposDocumentos,
                                                        [
                                                            'prompt' => [
                                                                'text' => 'Seleccione',
                                                                'options' => [
                                                                    'disabled' => true,
                                                                    'selected' => $model->isNewRecord ? true : false
                                                                ]
                                                            ],
                                                            'id' => 'tipo_dni_persona',
                                                            'class' => 'form-control input-md padding-top:6px;',
                                                            'disabled' => $model->isNewRecord ? false : true,
                                                            'onChange' => 'tipoDocumento()'
                                                        ]
                                                    );
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <div class="input-group required" id="idpersona_div">
                                                    <label class="control-label">Nro de documento</label>
                                                    <input class="form-control" type="text" maxlength="10" id="dni_persona" value="<?php echo $model->persona ?  $model->persona->documento : "" ?>" name="dni_persona" placeholder="Ingrese N° Documento" disabled>
                                                    <span class="input-group-btn">
                                                        <?= Html::button('<i class="glyphicon glyphicon-search"></i>', [
                                                            'id' => 'btn_dni_persona',
                                                            'name' => 'btn_dni_persona',
                                                            'class' => 'btn btn-primary btn-flat',
                                                            'style' => 'margin-top:26px',
                                                            'disabled' => true,
                                                            'title' => Yii::t('app', 'Buscar DNI'),
                                                        ]);
                                                        ?>
                                                        <?= Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                                                            'id' => 'btn_newPersona',
                                                            'name' => 'btn_newPersona',
                                                            'class' => 'btn btn-success btn-flat showModalButton',
                                                            'style' => 'margin-top:26px',
                                                            'disabled' => true,
                                                            'onclick' => 'abrirModalNewPersona()',
                                                            'title' => Yii::t('app', 'Crear Nueva Persona')
                                                        ]);
                                                        ?>
                                                        <?= Html::a(
                                                            '<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">',
                                                            null,
                                                            [
                                                                'id' => 'btn_pui_persona',
                                                                'name' => 'btn_pui_persona',
                                                                'data-request-method' => 'post',
                                                                'data-toggle' => 'tooltip',
                                                                'style' => 'margin-top:26px; padding:0px;padding-left:6px;',
                                                                'class' => 'btn',
                                                                'title' => Yii::t('app', 'Consulta a Portal Unificado')
                                                            ]
                                                        );
                                                        ?>
                                                    </span>
                                                </div>
                                                <span id="txt_mensaje_persona"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Apellido y Nombre</label>
                                                <input class="form-control" type="text" maxlength="10" id="apellido_nombre_persona" name="apellido_nombre_persona" value="<?php echo $model->persona ? $model->persona->apellido . " " . $model->persona->nombre : "" ?>" disabled>
                                            </div>
                                            <div class="col-md-12">
                                                <?= $form->field($model, 'idpersona')->hiddenInput()->label('') ?>
                                            </div>
                                        </div>
                                        <div class="row" id="idusuario_comprobante_div">
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'idusuario_comprobante')->widget(Select2::class, [
                                                    'data' => $listUsuario,
                                                    'options' => [
                                                        'placeholder' => 'Seleccione opción ...',
                                                        'tabIndex' => '1',
                                                        // 'onchange' =>   '',
                                                        // 'multiple' => true,
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'idcapa')->widget(Select2::class, [
                                                    'data' => $listCapa,
                                                    'options' => [
                                                        'placeholder' => 'Seleccione opción ...',
                                                        'tabIndex' => '1',
                                                        'onchange' =>   'cargarCapaItemOptions();',
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ]);
                                                ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'idlugar')->widget(Select2::class, [
                                                    'data' => $listLugar,
                                                    'options' => [
                                                        'placeholder' => 'Seleccione opción ...',
                                                        'tabIndex' => '1',
                                                        'disabled' =>  true,
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" id="monto_div">
                                                <?= $form->field($model, 'monto')->textInput(['id' => 'monto', 'min' => 0, 'max' => 100000000000, 'type' => 'number', 'step' => 'any', 'placeholder' => '$']); ?>
                                            </div>
                                            <div class="col-md-6" id="fecha_comprobante_div">
                                                <?=
                                                $form->field($model, 'fecha_comprobante')->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    'layout' => $model->isNewRecord ? '{picker}{input}{remove}' : '{picker}{input}',
                                                    'options' => [
                                                        'id' => 'fecha_comprobante',
                                                        'class' => 'form-control input-md',
                                                        'autocomplete' => 'off',
                                                        'placeholder' => '--/--/----'
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        'format' => 'dd/mm/yyyy',
                                                        'todayHighlight' => true,
                                                        'autoclose' => true
                                                    ],
                                                    'pluginEvents' => []
                                                ]);
                                                ?>
                                            </div>
                                            <div class="col-md-6" id="fecha_vale_div">
                                                <?=
                                                $form->field($model, 'fecha_vale')->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    'layout' => $model->isNewRecord ? '{picker}{input}{remove}' : '{picker}{input}',
                                                    'options' => [
                                                        'id' => 'fecha_vale',
                                                        'class' => 'form-control input-md',
                                                        'autocomplete' => 'off',
                                                        'placeholder' => '--/--/----'
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        'format' => 'dd/mm/yyyy',
                                                        'todayHighlight' => true,
                                                        'autoclose' => true
                                                    ],
                                                    'pluginEvents' => []
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?= $form->field($model, 'observaciones')->widget(Quill::class, [
                                                    'options' => [
                                                        'style' => 'height: 125px',
                                                        'id' => 'observaciones_texto',
                                                        'readonly' => $model->isNewRecord ? false : true
                                                    ],
                                                ]) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">Documentación Adjunta</label>

                                                <div class="adjuntar-text" style="display: flex; justify-content: flex-end"><i class="fa fa-upload"></i> Adjuntar archivos
                                                </div>

                                                <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                                                <input type="hidden" id="adjuntos_eliminados" name="Mds_legales_oficio[adjuntos_eliminados]">

                                                <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                                                    <div class="fallback">
                                                        <input name="file" type="file" />
                                                    </div>
                                                </div>
                                                <small class="text-muted" style="display: flex; justify-content: flex-end">La extension debe ser del tipo
                                                    ["pdf,jpeg,jpg,png,xls,xlsx"]. Tamaño máximo 50MB.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"><br />
                        <div class="col-md-12">
                            <a class="btn btn-info" href="javascript:history.back()" title="Volver">Volver</a> |
                            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'header' => '<h4>Agregar persona</h4>',
    'id' => 'modal_newPersona',
    'size' => 'modal-lg'
]);
?>
<div class="panel-body">
    <?php
    echo $this->render('./_form_new_persona', [
        'botones' => true,
        'generos' => $generos,
        'model_persona' => $model_persona,
        'nacionalidades' => $nacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'username' => $username,
        'token' => $token
    ]);
    ?>
</div>
<?php
Modal::end();
?>


<?php
$this->registerJs(
    "$(document).ready(function() {

        $('#btn_pui_persona').click(function(){
            let dni_campo = $('#dni_persona').val();
            window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
        });

        $('#btn_dni_persona').click(function(){
            const numeroDNI = $('#dni_persona').val();
            if (numeroDNI){
                getPersonaByDNI(numeroDNI);
            }
        });

        if($('#tipo').val()){
            ocultarCamposSegunTipo();
        }

        $('#dni_persona').on('input', function() {
            $('#btn_newPersona').prop('disabled', true);
            $('#txt_mensaje_persona').html('');
          });
    });
    "
);
?>

<script>
    function abrirModalNewPersona() {
        $('#DATOS_PERSONA_CONTAINER, #btn_dni_newpersona').hide();
        $('#modal_newPersona').modal('show');
        limpiarDatos()
        deshabilitar_controles();
        $('#txtDNI').val($('#dni_persona').val());
        $('#documento_tipo').val($('#tipo_dni_persona').val()).trigger('change');
        iniciarBusqueda();
    }

    function tipoDocumento() {
        $("#dni_persona").prop("disabled", false);
        $("#btn_dni_persona").prop("disabled", false);
    }

    function getPersonaByDNI(numeroDNI) {
        $("#txt_mensaje_persona").html(`Buscando datos de la Persona...`);
        $("#apellido_nombre_persona").val(``);
        const token = "<?= $token ?>";
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const url = "<?= env('ENDPOINT_API_SUR_NEST') ?>/<?= env('ENDPOINT_API_SUR_NEST_PERSONA_GET') ?>";
        const params = `/${numeroDNI}`;
        $.ajax({
            url: url + params,
            type: "GET",
            dataType: "json",
            async: true,
            headers,
            success: function(data) {
                if (data?.status === "success" && data?.records) {
                    const record = data.records;
                    $('#tipo_dni_persona').val(record.documento_tipo);
                    $("#dni_persona").val(`${record.documento}`);
                    $("#apellido_nombre_persona").val(`${record.apellido} ${record.nombre}`);
                    $("#mds_rendicion-idpersona").val(record.idpersona);
                    $("#txt_mensaje_persona").html(``);
                    $("#idpersona_div").removeClass('has-success has-error').addClass('has-success');
                    $("#btn_newPersona").prop("disabled", true);
                } else {
                    $("#txt_mensaje_persona").html(`<span style="color: red;">No se encontró en el sistema una persona con DNI: ${numeroDNI}. Por favor, debe registrarla</span>`);
                    $("#idpersona_div").removeClass('has-success has-error').addClass('has-error');
                    $("#btn_newPersona").prop("disabled", false);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                let errorMessage = "<span style='color: red'><b style='color: red'>Error!</b> <i>";
                const responseJSON = xhr.responseJSON;

                if (responseJSON && responseJSON.message) {
                    errorMessage += `${responseJSON.message}`;
                } else {
                    errorMessage += `Ocurrió un error al buscar a la persona`;
                }

                errorMessage += "</i></span>"
                $("#txt_mensaje_persona").html(errorMessage);
            },
        });
    }

    function cargarCapaItemOptions() {
        const idCapa = $("#mds_rendicion-idcapa").val();
        if (idCapa) {
            $.post(`index.php?r=sds_gis_capa_item/get_capaitem_by_idcapa&idcapa=${idCapa}`, function(data) {
                $("select#mds_rendicion-idlugar").html(data);
                $("#mds_rendicion-idlugar").val(null).trigger('change');
                $("#mds_rendicion-idlugar").prop("disabled", false);
            });
        }
    }

    function changeTipo() {
        ocultarCamposSegunTipo();
        limpiarCampos();
        limpiarDropzoneRendicion();
    }

    function ocultarCamposSegunTipo() {
        $('#rendicion_div').show();
        let idtipoSelect = $("#tipo option:selected").val();

        if (idtipoSelect == <?= $TIPO_COMBUSTIBLE ?>) {
            $("#persona_div").hide();
            $("#fecha_comprobante_div").hide();
            $("#fecha_comprobante_div").removeClass();

            $("#idusuario_comprobante_div").show();
            $("#idusuario_comprobante_div").addClass('required');
            $("#fecha_vale_div").show();
            $("#fecha_vale_div").removeClass().addClass('col-md-6 required');

            $(".field-mds_rendicion-idpersona div.help-block").html(``);
        }

        if (idtipoSelect == <?= $TIPO_AUH ?> || idtipoSelect == <?= $TIPO_ALIMENTAR ?>) {
            $("#persona_div").show();
            $("#fecha_comprobante_div").show();
            $("#fecha_comprobante_div").removeClass().addClass('col-md-6 required');

            $("#idusuario_comprobante_div").hide();
            $("#fecha_vale_div").hide();
            $("#fecha_vale_div").removeClass();
        }
    }

    function limpiarCampos() {
        $("#monto").val(``);
        $("#fecha_comprobante").val(``);
        $("#fecha_vale").val(``);

        $("#mds_rendicion-idpersona").val(``);
        $("#dni_persona").val(``);
        $("#apellido_nombre_persona").val(``);

        $("#observaciones_texto").val(``);
        let quill = document.getElementsByClassName("ql-editor");
        quill[0].innerHTML = "";

        $("#mds_rendicion-idcapa").val(null).trigger('change');
        $("#mds_rendicion-idlugar").val(null).trigger('change');
        $("#mds_rendicion-idlugar").prop("disabled", true);

    }

    function limpiarDropzoneRendicion() {
        // Esto limpia el dropzone
        let dropzone_documentos = document.querySelector("#adjunto-otrosdocumentos").dropzone;
        Dropzone.forElement("#adjunto-otrosdocumentos").removeAllFiles(true);
        $("#otros_adjuntos").val("");
    }
</script>