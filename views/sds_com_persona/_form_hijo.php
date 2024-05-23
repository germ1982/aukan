<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_persona */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="sds-com-persona-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'documento')
                ->textInput(['id' => 'txtDNI']) ?>
        </div>
        <div class="col-md-2" style="padding-top:25px;">
            <?php echo Html::a(
                '<i class="glyphicon glyphicon-search"></i>',
                null,
                [
                    'name' => 'btn_dni',
                    'id' => 'btn_dni',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-primary',
                    'title' => Yii::t('app', 'Consultar DNI'),
                ]
            ); ?>
        </div>
        <div class="col-md-6" style="padding-top:30px;" id="txt_mensaje">

        </div>
         </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'apellido')
                ->textInput(['maxlength' => true, 'disabled' => 'true']) ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'nombre')
                ->textInput(['maxlength' => true, 'disabled' => 'true']) ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'genero')
                ->dropDownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguraciones(
                            Sds_com_configuracion_tipo::TIPO_GENERO
                        ),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    ['prompt' => 'Seleccionar Género ...', 'disabled' => 'true']
                ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'nacionalidad')
                ->dropDownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguraciones(
                            Sds_com_configuracion_tipo::TIPO_NACIONALIDAD
                        ),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    [
                        'prompt' => 'Seleccionar Nacionalidad ...',
                        'disabled' => 'true',
                    ]
                ) ?>
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
                        'disabled' => true,
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
        <div class="col-md-2" style="padding-top:35px" id='div_conviviente'>
            <?= $form
                ->field($model, 'conviviente')
                ->checkBox(['id' => 'check_conviviente']) ?>
        </div>
    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                'class' => $model->isNewRecord
                    ? 'btn btn-success'
                    : 'btn btn-primary',
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJs(
    "$(document).ready(function() {        
        datos_persona(true);
    });

    $('#txtDNI').change(function(){        
        datos_persona(false);
    });

    $('#btn_dni').click(function(){        
        datos_persona(false);
    });
    "
); ?>

<script>
    var dni = <?php echo isset($model->dni) ? $model->dni : 0; ?>;

    function datos_persona(primera_vez = false) {
        var dni_campo = $('#txtDNI').val();
        if (dni != dni_campo || primera_vez) {
            if (dni_campo != '') {
                $('#txt_mensaje').html("Buscando datos de Persona...");
                dni = dni_campo;
                $.post("index.php?r=sds_com_persona/validar_dni&dni=" + dni, function(data) {
                    data = $.parseJSON(data);
                    if (data.length === 0) {
                        datos_renaper(dni);
                    } else {
                        $("#sds_com_persona-idpersona").val(data[0]['idpersona']);
                        $("#sds_com_persona-nombre").val(data[0]['nombre']);
                        $("#sds_com_persona-apellido").val(data[0]['apellido']);
                        $("#fecha_nacimiento").val(formatearFecha(data[0]['fecha_nacimiento']));
                        $("#sds_com_persona-nacionalidad").val(data[0]['nacionalidad']);
                        $("#sds_com_persona-genero").val(data[0]['genero']);
                        $('#txt_mensaje').html("");
                        habilitar_controles();
                    }
                });
            }
        }
    }

    function datos_renaper(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            if (data.status == "error") {
                $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos();
            } else {
                var nombre = "";
                var apellido = "";
                var domicilio = "";
                var localidad = "";
                var foto = "";
                var fecha_nacimiento = null;
                $.each(data, function(ind, elem) {
                    if (ind == 'records') {
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
                        domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                        localidad = elem[0].result.ciudad;
                        //foto = elem[0].result.foto;
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                    }
                });
                if (fecha_nacimiento != null) {
                    $("#sds_com_persona-nombre").val(corregir_palabra(nombre));
                    $("#sds_com_persona-apellido").val(corregir_palabra(apellido));
                    $("#fecha_nacimiento").val(fecha_nacimiento);
                    $("#sds_com_persona-nacionalidad").val('');
                    $("#sds_com_persona-genero").val('');
                    $("#sds_com_persona-domicilio").val(domicilio);
                    $("#sds_com_persona-localidad").val(getIdLocalidad(corregir_palabra(localidad)));
                    /* $("#renaper_foto").attr("src", foto); */
                    $('#txt_mensaje').html("");
                    habilitar_controles();
                }
            }
        });
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#sds_com_persona-nombre").val('');
        $("#sds_com_persona-apellido").val('');
        $("#fecha_nacimiento").val('');
        $("#sds_com_persona-nacionalidad").val('');
        $("#sds_com_persona-sexo").val('');
        $("#sds_com_persona-telefono").val("");
        $("#sds_com_persona-domicilio").val("");
        $("#sds_com_persona-localidad").val("");
        $("#sds_com_persona-idpersona").val('0');
    }

    function getIdLocalidad(localidad) {
        $.post("index.php?r=sds_800_llamada/get_id_localidad&localidad=" + localidad, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                return "";
            } else {
                $("#sds_800_llamada-localidad").val(data['idlocalidad']);
            }
        });
    }

    function habilitar_controles() {
        $("#sds_com_persona-nombre").prop("disabled", false);
        $("#sds_com_persona-apellido").prop("disabled", false);
        $("#fecha_nacimiento").prop("disabled", false);
        $("#sds_com_persona-nacionalidad").prop("disabled", false);
        $("#sds_com_persona-genero").prop("disabled", false);
        $("#sds_com_persona-telefono").prop("disabled", false);
        $("#sds_com_persona-domicilio").prop("disabled", false);
        $("#sds_com_persona-localidad").prop("disabled", false);
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }
</script>