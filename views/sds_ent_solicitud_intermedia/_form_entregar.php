<?php

use app\models\Mds_org_documento;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_entrega;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

use app\models\Sds_ent_tipo;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_entrega */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="sds-ent-entrega-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'usuario_entrega')
                ->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Mds_seg_usuario::find()
                            ->where(['activo' => '1'])
                            ->orderBy(['user' => SORT_ASC])
                            ->all(),
                        'idusuario',
                        'user'
                    ),
                    'options' => [
                        'placeholder' => 'Seleccionar Responsable ...',
                        'id' => 'cmb_usuario',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])
                ->label('Usuario Responsable') ?>
        </div>
    </div>
    <div class="row">
        <div id="num_desde" class="col-md-3" style="display:none;">
            <?= $form
                ->field($model, 'numero_desde')
                ->textInput(['tabIndex' => '1']) ?>
        </div>
        <div id="num_hasta" class="col-md-3" style="display:none;">
            <?= $form
                ->field($model, 'numero_hasta')
                ->textInput(['tabIndex' => '1']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion_renaper">
                <div class="panel panel-accordion">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#renaper">
                                Persona que Retira
                            </a>
                        </h4>
                    </div>
                    <div id="renaper" class="accordion-body collapse in">
                        <div class="panel-body" id="renaper_content">
                            <div class="row">
                                <!-- <div class="col-md-3" style="text-align: center;">
                                    <img id="renaper_foto" src="" alt="" width="100%" />
                                </div> -->
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12" style="text-align: center;" id="txt_mensaje"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'dni')
                                                ->textInput([
                                                    'id' => 'txtDNI',
                                                    'tabIndex' => '1',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-2" style="padding-top:25px;">
                                            <?php echo Html::a(
                                                '<i class="glyphicon glyphicon-search"></i>',
                                                null,
                                                [
                                                    'name' => 'btn_dni',
                                                    'id' => 'btn_dni',
                                                    'data-request-method' =>
                                                        'post',
                                                    'data-toggle' => 'tooltip',
                                                    'class' =>
                                                        'btn btn-primary',
                                                    'title' => Yii::t(
                                                        'app',
                                                        'Consultar DNI Persona'
                                                    ),
                                                ]
                                            ); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= $form
                                                ->field($model, 'nombre')
                                                ->textInput([
                                                    'tabIndex' => '1',
                                                ]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= $form
                                                ->field($model, 'apellido')
                                                ->textInput([
                                                    'tabIndex' => '1',
                                                ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $form
        ->field($model, 'persona_retira')
        ->hiddenInput()
        ->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    'id' => 'modal_abm',
    'size' => 'modal-md',
]);

echo "<div id='content_abm'></div>";

Modal::end();

$this->registerJs(
    "
    function formatearFecha(fecha) {
        var day = fecha.substring(0, 2);
        var month = fecha.substring(3, 5);
        var year = fecha.substring(6, 10);
        var today = year + \"-\" + month + \"-\" + day;
        return today;
    }
    
    jQuery.extend(jQuery.expr[':'], {
        focusable: function (el, index, selector) {          
            /* return ($(el).is(':input') || $(el).attr('tabindex')>0)
            || ($(el).is('a,button') && $(el).attr('tabindex')>0); */  
            return $(el).attr('tabindex')>0;
        }
    });
    
    /* $( ':focusable' ).css( 'border-color', '#FF9933' );  */
    
    $(document).on('keypress', 'input,select,a,button', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            // Get all focusable elements on the page
            var canfocus = $(':focusable');            
            var index = canfocus.index(this) + 1;
            if (index >= canfocus.length) index = 0;      
            canfocus.eq(index).focus();            
        }
    });

    $('#txtDNI').focusout(function(){
        datos_persona(false);
    });
    $('#btn_dni').click(function(){
        datos_persona(false);
    });

    $(document).ready(function() {        
        habilitarNumero();
    });
    
    "
);
?>



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
                        $("#sds_ent_entrega-persona_retira").val(data[0]['idpersona']);
                        $("#sds_ent_entrega-nombre").val(data[0]['nombre']);
                        $("#sds_ent_entrega-apellido").val(data[0]['apellido']);
                        /* $("#renaper_foto").attr("src", ''); */
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
                $.each(data, function(ind, elem) {
                    console.log(ind);
                    if (ind == 'records') {
                        console.log(elem[0].result);
                        nombre = corregir_palabra(elem[0].result.nombres);
                        apellido = corregir_palabra(elem[0].result.apellido);
                        domicilio = corregir_palabra(elem[0].result.calle + " " + elem[0].result.numero);
                        localidad = corregir_palabra(elem[0].result.ciudad);
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                        //foto = elem[0].result.foto;
                    }
                });
                $("#sds_ent_entrega-persona_retira").val(0);
                $("#sds_ent_entrega-nombre").val(nombre);
                $("#sds_ent_entrega-apellido").val(apellido);
                /* $("#renaper_foto").attr("src", foto); */
                $('#txt_mensaje').html("");
                habilitar_controles();
            }
        });
    }

    function corregir_palabra(palabra) {
        console.log(palabra);
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#sds_ent_entrega-nombre").val('');
        $("#sds_ent_entrega-apellido").val('');
        /* $("#renaper_foto").attr("src", ''); */
        $("#sds_ent_entrega-persona_retira").val('0');
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }

    function habilitar_controles() {
        $("#fecha_entrega").prop("disabled", false);
        $("#hora").prop("disabled", false);
        $("#cmb_tipo").prop("disabled", false);
        $("#sds_ent_entrega-numero").prop("disabled", false);
        $("#cmb_emisor").prop("disabled", false);
        $("#sds_ent_entrega-cantidad").prop("disabled", false);
        $("#sds_ent_entrega-observaciones").prop("disabled", false);
        $("#sds_ent_entrega-nombre").prop("disabled", false);
        $("#sds_ent_entrega-apellido").prop("disabled", false);
        $("#fecha_nacimiento").prop("disabled", false);
        $("#sds_ent_entrega-nacionalidad").prop("disabled", false);
        $("#sds_ent_entrega-sexo").prop("disabled", false);
    }

    function habilitarNumero() {
        var idtipo = <?= $model->idtipo ?>;
        if (idtipo != '') {
            $.post("index.php?r=sds_ent_entrega/habilitar_numero&idtipo=" + idtipo, function(data) {
                if (data == 1) {
                    $("#num_desde").show();
                    $("#num_hasta").show();
                } else {
                    $("#num_hasta").hide();
                }
            });
        }
    }
</script>