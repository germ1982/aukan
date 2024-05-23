<?php

use app\controllers\SiteController;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_oficina;
use app\models\Mds_org_organismo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_contacto */
/* @var $form yii\widgets\ActiveForm */

$organigrama = isset($organigrama) ? $organigrama : 0;

$user = Yii::$app->user->identity;
$idusuario = $user != null ? $user->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app
        ->getResponse()
        ->redirect(['site/login', 'model' => $model]);
}

$array_servicios = Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ORG_SERVICIO);
$array_dispositivos = Mds_org_dispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all();

?>
<div class="mds-org-contacto-form">
    <?php $form = ActiveForm::begin([
        'action' => [
            'mds_org_contacto/' .
                ($model->isNewRecord
                    ? 'create' . (isset($botones) ? '_ext' : '')
                    : 'update'),
            'id' => $model->idcontacto,
            'organigrama' => $organigrama,
        ],
        'id' => $model->formName(),
    ]); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'documento')
                ->textInput(['id' => 'txtDNI']) ?>
        </div>
        <div class="col-md-3" style="padding-top:25px;">
            <?php echo Html::a(
                '<i class="glyphicon glyphicon-search"></i>',
                null,
                [
                    'name' => 'btn_dni',
                    'id' => 'btn_dni',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-primary',
                    'title' => Yii::t('app', 'Consultar DNI Llamante'),
                ]
            ); ?>
            <?php echo Html::a(
                '<i class="glyphicon glyphicon-pencil"></i>',
                null,
                [
                    'id' => 'btnEditarExistente',
                    'style' => 'display:none',
                    'class' => 'btn btn-primary',
                    'role' => 'modal-remote',
                    'title' => 'Modificar contacto existente',
                ]
            ); ?>
        </div>
        <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">
        </div>
        <!-- <div class="col-md-3" style="text-align: right;">
            <img id="renaper_foto" src="" alt="" height="75px" />
        </div> -->
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form
                ->field($model, 'legajo')
                ->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form
                ->field($model, 'ubicacion_fisica')
                ->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form
                ->field($model, 'norma_legal')
                ->textInput(['disabled' => false]) ?>
        </div>
        <div class="col-md-3">
            <?= $form
                ->field($model, 'nombre')
                ->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form
                ->field($model, 'apellido')
                ->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'nacionalidad')
                ->dropdownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguraciones(
                            Sds_com_configuracion_tipo::TIPO_NACIONALIDAD,
                            false
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
            <?= $form
                ->field($model, 'sexo')
                ->dropDownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguraciones(
                            Sds_com_configuracion_tipo::TIPO_GENERO
                        ),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    ['prompt' => 'Seleccionar Sexo ...', 'disabled' => true]
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
                ->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'disabled' => true,
                    'options' => [
                        'class' => 'form-control input-md',
                        'placeholder' => 'DD / MM / YYYY',
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'endDate' => date('d/m/Y'),
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ],
                ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'mail')
                ->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'telefono')
                ->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top: 33px;">
            <?= $form->field($model, 'ficha')->checkbox(['checked' => ($model->isNewRecord ? true : ($model->ficha ? true : false))]) ?>
        </div>
        <div class="col-md-2" style="padding-top: 33px;">
            <?= $form->field($model, 'retenido')->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2" style="text-align: left;">
            <?= $form->field($model, 'interno')->checkbox() ?>
        </div>
        <div class="col-md-2" style="text-align: center;">
            <?= $form->field($model, 'rotativo')->checkbox() ?>
        </div>
        <div class="col-md-2" style="text-align: left;">
            <?= $form->field($model, 'acompaniante')->checkbox() ?>
        </div>
        <div class="col-md-2" style="text-align: center;">
            <?= $form->field($model, 'esencial')->checkbox() ?>
        </div>
        <div class="col-md-2" style="text-align: right;">
            <?= $form->field($model, 'activo')->checkbox() ?>
        </div>
        <div class="col-md-2" style="text-align: right;">
            <?= $form->field($model, 'turno_rotativo')->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'tipo_contratacion')
                ->widget(Select2::class, [
                    'data' => [
                        Mds_org_contacto::TIPO_CONTRATACION_CONTRATO =>
                        'Contratado',
                        Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES =>
                        'Eventual',
                        Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE =>
                        'Planta Permanente',
                        Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA =>
                        'Planta Política',
                        Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA_PURA =>
                        'Planta Política Pura',
                    ],
                    'options' => [
                        'placeholder' => 'Seleccionar Tipo Contratación ...',
                        'disabled' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>
        </div>
        <div class="col-md-4">
            <?=SiteController::actionGet_input_select2($form,$model,'servicio','cmb_servicio',$array_servicios,'idconfiguracion','descripcion','Servicio','Ingrese Servicio...')?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'categoria')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_com_configuracion::getConfiguraciones(
                        Sds_com_configuracion_tipo::TIPO_CATEGORIA_CONVENIO
                    ),
                    'idconfiguracion',
                    'descripcion'
                ),
                'options' => [
                    'placeholder' => 'Seleccionar Categoría ...',
                    'disabled' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php if ($model->fecha_ingreso != null) {
                $model->fecha_ingreso = date(
                    'd/m/Y',
                    strtotime(str_replace('/', '-', $model->fecha_ingreso))
                );
            } ?>
            <?= $form
                ->field($model, 'fecha_ingreso')
                ->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}',
                    'disabled' => true,
                    'options' => [
                        'class' => 'form-control input-md',
                        'placeholder' => 'DD/MM/YYYY',
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ],
                ]) ?>
        </div>

        <div class="col-md-6">
            <?php if ($model->fecha_ingreso_planta != null) {
                $model->fecha_ingreso_planta = date(
                    'd/m/Y',
                    strtotime(
                        str_replace('/', '-', $model->fecha_ingreso_planta)
                    )
                );
            } ?>
            <?= $form
                ->field($model, 'fecha_ingreso_planta')
                ->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}',
                    'disabled' => true,
                    'options' => [
                        'class' => 'form-control input-md',
                        'placeholder' => 'DD/MM/YYYY',
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ],
                ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'titulo')->textInput([
                'maxlength' => true,
                'disabled' => true,
                'label' => 'Título',
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textInput([
                'maxlength' => true,
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <?= $form
                        ->field($model, 'organismo_search')
                        ->textInput([
                            'placeholder' => 'Buscar...',
                            'id' => 'buscar_arbol',
                        ])
                        ->label('Organismo') ?>
                </div>
            </div>
            <div class="row">
                <div id="tree_organismos" class="col-md-12">
                    <!-- La parte de UI del árbol se genera a partir del javascript 
                            de la pagina de ejemplo template/javascripts/ui-elements/examples.treeview.js
                            ya agregados en AppAsset-->
                    <ul>
                        <?php
                        /* $organismo_raiz = Mds_org_organismo::getOrganismoRaiz();
                        echo Mds_org_organismo::getArbolOrganigrama($organismo_raiz,); */
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <?= 
                    SiteController::actionGet_input_select2($form,$model,'iddispositivo','cmb_dispositivo',$array_dispositivos,'iddispositivo',function ($model) {
                        return $model->descripcion;
                    },'Dispositivoo')
                /* $form->field($model, 'iddispositivo')->dropDownList(
                    ArrayHelper::map(
                        Mds_org_dispositivo::find()
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                        'iddispositivo',
                        function ($model) {
                            return $model->descripcion;
                        }
                    ),
                    [
                        'prompt' => 'Seleccionar Dispositivo ...',
                        'id' => 'cmb_dispositivo',
                        'disabled' => true,
                    ]
                ) */ ?>
                <span class="input-group-btn">
                    <?= Html::button(
                        '<i class="glyphicon glyphicon-plus"></i>',
                        [
                            'value' => Url::to([
                                'mds_org_dispositivo/create',
                                'idorganismo',
                            ]),
                            'class' => 'btn btn-success btn-flat',
                            'id' => 'btnDispositivo',
                            'style' => 'margin-top:27px',
                            'disabled' => true,
                            'onclick' => '$("#abm_dispositivo").show();
                        $("#btnGuardar").hide();$("#btnCerrar").hide();
                        $("#btnGuardarInterno").hide();$("#btnCerrarInterno").hide();',
                        ]
                    ) ?>
                </span>
            </div>
            <div style="margin-top:15px;">
                <?= $form
                    ->field($model, 'perfil')
                    ->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(
                                Sds_com_configuracion_tipo::TIPO_ORG_PERFIL
                            ),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => 'Seleccionar Perfil ...',
                            'disabled' => 'true',
                        ]
                    ) ?>
            </div>
            <div style="margin-top:15px;">
                <?= $form
                    ->field($model, 'actividad')
                    ->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(
                                Sds_com_configuracion_tipo::TIPO_ORG_ACTIVIDAD
                            ),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => 'Seleccionar Actividad ...',
                            'disabled' => 'true',
                        ]
                    ) ?>
            </div>
            <div style="margin-top:15px;">
                <?= $form->field($model, 'idoficina')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        Mds_org_oficina::find()
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                        'idoficina',
                        'descripcion'
                    ),
                    'options' => [
                        'placeholder' => 'Seleccionar Oficina ...',
                        'disabled' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="crear_usuario" class="col-md-offset-8 col-md-4 " style="text-align: right;">
            <?= $model->isNewRecord
                ? $form->field($model, 'crear_usuario')->checkbox()
                : '' ?>
        </div>
    </div>
    <?= $form
        ->field($model, 'idpersona')
        ->hiddenInput()
        ->label(false) ?>
    <?php if (isset($botones)) { ?>
        <br>
        <div class="form-group">
            <?= Html::submitButton(
                $model->isNewRecord ? 'Guardar' : 'Actualizar',
                [
                    'class' => $model->isNewRecord
                        ? 'btn btn-success'
                        : 'btn btn-primary',
                    'id' => 'btnGuardarInterno',
                ]
            ) ?>
            <?= Html::button('Cerrar', [
                'id' => 'btnCerrarInterno',
                'class' => 'btn btn-default',
                'onclick' => '$("#abm_contacto").hide();
                //Vuelvo a mostrar los botones ocultos del padre
                $("#btnGuardar").show();
                $("#btnCerrar").show();
                $("#main_form").show();',
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<JS
    var dni = "$model->documento"!="" ? "$model->documento" : "0";
    var idcontacto = "$model->idcontacto"!="" ? "$model->idcontacto" : "0";
    var organigrama = $organigrama;

    function habilitar_controles() {
        var deshabilitar = !$('#mds_org_contacto-activo').prop('checked');
        $("#mds_org_contacto-activo").prop("disabled", false);        
        $("#mds_org_contacto-turno_rotativo").prop("disabled", false);
        $("#mds_org_contacto-legajo").prop("disabled", deshabilitar);
        $("#mds_org_contacto-ubicacion_fisica").prop("disabled", deshabilitar);
        $("#mds_org_contacto-nombre").prop("disabled", deshabilitar);
        $("#mds_org_contacto-apellido").prop("disabled", deshabilitar);
        $("#mds_org_contacto-fecha_nacimiento").prop("disabled", deshabilitar);
        $("#mds_org_contacto-telefono").prop("disabled", deshabilitar);
        $("#mds_org_contacto-mail").prop("disabled", deshabilitar);
        $("#mds_org_contacto-nacionalidad").prop("disabled", deshabilitar);
        $("#mds_org_contacto-sexo").prop("disabled", deshabilitar);        
        $("#mds_org_contacto-interno").prop("disabled", deshabilitar);
        $("#mds_org_contacto-rotativo").prop("disabled", deshabilitar);        
        $('#mds_org_contacto-acompaniante').prop("disabled", deshabilitar);
        $('#mds_org_contacto-esencial').prop("disabled", deshabilitar);
        $('#mds_org_contacto-tipo_contratacion').prop("disabled", deshabilitar);
        $('#mds_org_contacto-categoria').prop("disabled", deshabilitar);
        //$('#mds_org_contacto-actividad').prop("disabled", $("#mds_org_contacto-tipo_contratacion").val()==2||$("#mds_org_contacto-tipo_contratacion").val()==null);
        $('#mds_org_contacto-actividad').prop("disabled", deshabilitar);
        $('#mds_org_contacto-perfil').prop("disabled", !$('#mds_org_contacto-acompaniante').prop('checked'));
        $("#mds_org_contacto-idoficina").prop("disabled", deshabilitar);
        $("#mds_org_contacto-titulo").prop("disabled", deshabilitar);
        $("#mds_org_contacto-fecha_ingreso_planta").prop("disabled", deshabilitar);
        $("#mds_org_contacto-fecha_ingreso").prop("disabled", deshabilitar);
        $("#cmb_organismo").prop("disabled", deshabilitar);
        $("#cmb_dispositivo").prop("disabled", deshabilitar);
        $("#btnDispositivo").prop("disabled", deshabilitar);
        $("#mds_org_contacto-ficha").prop("disabled", deshabilitar);
        $("#mds_org_contacto-retenido").prop("disabled", deshabilitar);
        if (!deshabilitar){
            $('#tree_organismos li').each(function() {
                $("#tree_organismos").jstree().enable_node(this.id);
            });    
            var idorganismo = "$model->idorganismo"!="" ? "$model->idorganismo":0;
            if (idorganismo!=0){
                $('#tree_organismos').jstree(true).select_node(idorganismo);
                cargarDispositivos(idorganismo);
            }
        }
        else {            
            $('#tree_organismos li').each( function() {
                $("#tree_organismos").jstree().disable_node(this.id);    
            });
            $('#tree_organismos').jstree(true).select_node(1);            
            cargarDispositivos(1);            
        }
    }

    function datos_persona(primera_vez = false) {
        var dni_campo = $('#txtDNI').val();
        if (dni != dni_campo || primera_vez) {
            if (dni_campo != '') {
                $('#txt_mensaje').html("Buscando datos de Persona...");
                dni = dni_campo;
                $.post("index.php?r=mds_org_contacto/validar_dni&idcontacto=" + idcontacto + "&dni=" + dni, function(data) {
                    data = $.parseJSON(data);
                    if (data.length === 0) {
                        datos_renaper(dni);
                    } else {
                        console.log(data);
                        if (!data.idcontacto) {
                            $("#btnEditarExistente").hide();
                            $("#mds_org_contacto-idpersona").val(data[0]['idpersona']);
                            $("#mds_org_contacto-nombre").val(data[0]['nombre']);
                            $("#mds_org_contacto-apellido").val(data[0]['apellido']);
                            $("#mds_org_contacto-fecha_nacimiento").val(formatearFecha(data[0]['fecha_nacimiento']));
                            $("#mds_org_contacto-nacionalidad").val(data[0]['nacionalidad']);
                            $("#mds_org_contacto-sexo").val(data[0]['genero']);
                            $('#txt_mensaje').html('');
                            /* $("#renaper_foto").attr("src", ''); */
                        } else {
                            $('#txt_mensaje').html('DNI vinculado a un contacto ya existente. Haga click en el lápiz para editarlo.');
                            $("#btnEditarExistente").show();
                            $("#btnEditarExistente").attr("href", "index.php?r=mds_org_contacto/update&id=" + data.idcontacto + "");
                        }                        
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
                var fecha_nacimiento = null;
                //var sexo = "";
                var nacionalidad = "";
                $.each(data, function(ind, elem) {
                    console.log(ind);
                    if (ind == 'records') {
                        console.log(elem[0]);
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
                        domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                        localidad = elem[0].result.ciudad;
                        //foto = elem[0].result.foto;
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                    }
                });
                if (fecha_nacimiento != null) {
                    $("#mds_org_contacto-idpersona").val('0');
                    $("#mds_org_contacto-nombre").val(corregir_palabra(nombre));
                    $("#mds_org_contacto-apellido").val(corregir_palabra(apellido));
                    $("#mds_org_contacto-fecha_nacimiento").val(fecha_nacimiento);
                    $("#mds_org_contacto-nacionalidad").val('');
                    $("#mds_org_contacto-sexo").val('');
                    $("#mds_org_contacto-domicilio").val(domicilio);
                    $("#mds_org_contacto-localidad").val('');
                    /* $("#renaper_foto").attr("src", foto); */
                    $('#txt_mensaje').html("");
                    habilitar_controles();
                }
            }            
        });
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#mds_org_contacto-nombre").val('');
        $("#mds_org_contacto-apellido").val('');
        $("#ds_org_contacto-fecha_nacimiento").val('');
        $("#mds_org_contacto-nacionalidad").val('');
        $("#mds_org_contacto-sexo").val('');
        $("#mds_org_contacto-telefono").val("");
        $("#mds_org_contacto-domicilio").val("");
        $("#mds_org_contacto-localidad").val("");
        /* $("#renaper_foto").attr("src", ''); */
        $("#mds_org_contacto-idpersona").val(0);
    }

    function habilitar_solo_controles_de_contacto() {
        $("#mds_org_contacto-legajo").prop("disabled", false);
        $("#mds_org_contacto-ubicacion_fisica").prop("disabled", false);
        $("#mds_org_contacto-telefono").prop("disabled", false);
        $("#mds_org_contacto-mail").prop("disabled", false);
        $("#cmb_organismo").prop("disabled", false);
        $("#cmb_dispositivo").prop("disabled", false);
        $("#btnDispositivo").prop("disabled", false);
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

    function componentFromStr(numStr, percent) {
        var num = Math.max(0, parseInt(numStr, 10));
        return percent ?
            Math.floor(255 * Math.min(100, num) / 100) : Math.min(255, num);
    }

    function rgbToHex(rgb) {
        var rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/;
        var result, r, g, b, hex = "";
        if ((result = rgbRegex.exec(rgb))) {
            r = componentFromStr(result[1], result[2]);
            g = componentFromStr(result[3], result[4]);
            b = componentFromStr(result[5], result[6]);

            hex = "0x" + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1);
        }
        return hex;
    }

    function despintar(obj) {
        var color = obj.style.backgroundColor;
        var color2 = rgbToHex(color);
        if (color2 == '0xb3e5ff') {} else {
            obj.style.backgroundColor = "transparent";
        }
    }

    function pintar(obj) {
        var color = obj.style.backgroundColor;
        var color2 = rgbToHex(color);
        if (color2 == '0xb3e5ff') {} else {
            obj.style.backgroundColor = "#e6f7ff";
        }
    }

$('form#{$model->formName()}').on('beforeSubmit',function(e){    
    var \$form = $(this);
    $.post(
        \$form.attr("action"),
        \$form.serialize()
    )
    .done(function(result){    
        if(result > 0){
            $(\$form).trigger("reset");      
            $('#abm_contacto').hide(); 
            e.preventDefault();
            $.post("index.php?r=mds_org_contacto/cmb_contacto", function(data) {
                $("select#cmb_contacto").html(data);
                $("select#cmb_contacto").val(result);                
                //Vuelvo a mostrar los botones ocultos del padre
                $("#btnGuardar").show();
                $("#btnCerrar").show();
                $("#main_form").show();
            });            
        }else{
            $("#message").html(result);
        }
    }).fail(function(){
        console.log("server error");
    });
   
    return false;
});

function cargarDispositivos(idorganismo) {
    $("select#mds_org_dispositivo-idorganismo").val(idorganismo).trigger("change");
    //$("select#mds_org_dispositivo-idorganismo").attr('disabled', 'disabled');
    $.post("index.php?r=mds_org_dispositivo/cmb_dispositivo&idorganismo=" + idorganismo, function(data) {
            $("select#cmb_dispositivo").html(data);            
            var iddispositivo = "$model->iddispositivo"!="" ? "$model->iddispositivo":0;
            $("select#cmb_dispositivo").val(iddispositivo);
            if (!$('#mds_org_contacto-activo').prop('checked')){
                $("select#cmb_dispositivo").val(330);
            }
    });
}

function cargarCategorias(){
    //83 planta politica | 84 convenio | 87 Contrato
    //'0: Planta Política; 1: Planta Permanente; 2: Eventuales; 3: Contrato; 4: Planta Política Pura'
    var tipoCateg = $("#mds_org_contacto-tipo_contratacion").val()==0
                    || $("#mds_org_contacto-tipo_contratacion").val()==4 ? 83 : 
                    ($("#mds_org_contacto-tipo_contratacion").val()==1 ||
                    $("#mds_org_contacto-tipo_contratacion").val()==2 ? 84:87);
    //$('#mds_org_contacto-actividad').prop("disabled", $("#mds_org_contacto-tipo_contratacion").val()==2);

    $.post("index.php?r=sds_com_configuracion/cmb_config&tipo="+tipoCateg, function(data) {
        if (data!="<option value=null></option>"){
            $("select#mds_org_contacto-categoria").html(data);
        }
        else {
            $("select#mds_org_contacto-categoria").html("");
        }        
    });
}

$(document).ready(function() {
    $("#mds_org_contacto-activo").prop("disabled", true);
    $("#mds_org_contacto-turno_rotativo").prop("disabled", true);
    $("#mds_org_contacto-interno").prop("disabled", true);
    $("#mds_org_contacto-rotativo").prop("disabled", true);
    $('#mds_org_contacto-acompaniante').prop("disabled", true);
    $('#mds_org_contacto-esencial').prop("disabled", true);
    $('#mds_org_contacto-ficha').prop("disabled", true);
    $('#mds_org_contacto-retenido').prop("disabled", true);
    //cargarCategorias();
    cargarArbol(true);       
});

$('#txtDNI').change(function(){        
    datos_persona(false);
});

$('#mds_org_contacto-tipo_contratacion').change(function(){        
    cargarCategorias();
    var eventual = $("#mds_org_contacto-tipo_contratacion").val()==2;
    //$('#mds_org_contacto-actividad').prop("disabled", !eventual);    
});

$('#mds_org_contacto-acompaniante').change(function(){    
    $('#mds_org_contacto-perfil').prop("disabled", !$('#mds_org_contacto-acompaniante').prop('checked'));
});

$('#mds_org_contacto-activo').change(function(){       
   habilitar_controles();   
});

$('#btn_dni').click(function(){        
    datos_persona(false);
});

$('#buscar_arbol').keyup(function(){         
    setTimeout(() => {cargarArbol();}, 1000);
});

function cargarArbol(primera_vez = false) {
    var descripcion = $('#buscar_arbol').val();
    var idusuario = "$idusuario"!="" ? "$idusuario":0;
    $("#loading").show();
    $.post("index.php?r=mds_org_organismo/reload_organigrama&descripcion=" + descripcion + "&usuario="+idusuario, function(data) {        
        $("#tree_organismos").jstree(true).settings.core.data = data;
        $("#tree_organismos").jstree(true).refresh();
        if (primera_vez){
            $('#tree_organismos li').each( function() {
                $("#tree_organismos").jstree().disable_node(this.id);    
            });
            datos_persona(true); 
        }
        $("#loading").hide();
    });
}
    
$('#tree_organismos').jstree({
    'core' : {
        'themes' : {
            'responsive': false
        }
    },
    'types' : {
        'default' : {
            'icon' : 'fa fa-folder'
        },
        'file' : {
            'icon' : 'fa fa-file'
        }
    },
    'plugins': ['types']
});
    
$('#tree_organismos').on('activate_node.jstree', function (e, data) {
        if (data == undefined || data.node == undefined || data.node.id == undefined)
                return;
        var idorganismo=data.node.id;
        cargarDispositivos(idorganismo);
    }
);        

JS;

$this->registerJs($script);
?>

<div class="row" id="abm_dispositivo" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Dispositivo
                </h3>
            </header>
            <div class="panel-body">
                <?php
                $model_dispositivo = new Mds_org_dispositivo();
                echo $this->render('/mds_org_dispositivo/_form', [
                    'model' => $model_dispositivo,
                    'botones' => true,
                ]);
                ?>
            </div>
        </section>
    </div>
</div>