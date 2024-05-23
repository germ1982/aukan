<?php

use app\models\Mds_cap_instancia;
use app\models\Mds_cap_persona;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_com_configuracion;
use kartik\date\DatePicker;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<script>
    $('#nombre_persona').prop("disabled", true);
    $('#apellido_persona').prop("disabled", true);
    $('#fecha_nacimiento').prop("disabled", true);
    $('#nacionalidad_persona').prop("disabled", true);
    $('#sexo_persona').prop("disabled", true);
    $('#telefono_persona').prop("disabled", true);
    $('#mail_persona').prop("disabled", true);
</script>

<div class="mds-cap-persona-form">

    <?php $form = ActiveForm::begin([
        'action' => [
            'mds_cap_persona/' .
            ($model->isNewRecord
                ? 'create' . (isset($botones) ? '_ext' : '')
                : 'update'),
            'id' => $model->idpersonacap,
        ],
        'id' => $model->formName(),
    ]); ?>

    <!--?php $form = ActiveForm::begin(); ?-->
    <!-- ##################################################################################################################################################### -->
    <div class="row">
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <div class="col-md-4">
            <?= $form->field($model, 'dni')->textInput([
                'id' => 'txtDNI',
                'disabled' => !$model->isNewRecord,
            ]) ?>
        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->

        <?php if ($model->isNewRecord) { ?>
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
                    ]
                ); ?>
            </div>
        <?php } ?>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <div class="col-md-6" style="padding-top:30px;" id="txt_mensaje">

        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <!-- <div class="col-md-3" style="text-align: right;">
            <img id="renaper_foto" src="" alt="" height="75px" />
        </div> -->
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    </div>

    <!-- ##################################################################################################################################################### -->
    <div class="row">
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <div class="col-md-4">
            <?= $form
                ->field($model, 'nombre')
                ->textInput(['id' => 'nombre_persona']) ?>
        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <div class="col-md-4">
            <?= $form
                ->field($model, 'apellido')
                ->textInput(['id' => 'apellido_persona']) ?>
        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
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
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    </div>
    <!-- ##################################################################################################################################################### -->
    <div class="row">
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
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
                        'placeholder' => 'Seleccionar Nacionalidad ...',
                        'tabindex' => '1',
                    ]
                ) ?>
        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->

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
                        'placeholder' => 'Seleccionar Genero ...',
                        'tabindex' => '1',
                    ]
                ) ?>
        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->

    </div>
    <!-- ##################################################################################################################################################### -->
    <div class="row">
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <div class="col-md-8">
            <?= $form
                ->field($model, 'mail')
                ->textInput(['id' => 'mail_persona', 'maxlength' => true])
                ->label('E-mail') ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'telefono')
                ->textInput(['id' => 'telefono_persona']) ?>
        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    </div>

    <div class="row">
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <div class="col-md-4">
            <?= $form->field($model, 'localidad')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_com_localidad::getLocalidadesMostrar(),
                    'idlocalidad',
                    'descripcion'
                ),
                'options' => [
                    'placeholder' => 'Seleccionar Localidad ...',
                    'id' => 'localidad_victima',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <?= $form
                    ->field($model, 'ultimo_año')
                    ->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguracionesActivas(
                                Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO,
                                true
                            ),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => '-- Seleccione una opción --',
                            'id' =>
                                'config_' .
                                Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO,
                        ]
                    ) ?>

            </div>
        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    </div>

    <!-- ##################################################################################################################################################### -->

    <?= $form
        ->field($model, 'idpersona')
        ->hiddenInput(['id' => 'hidden_nueva_persona'])
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
                'onclick' => '$("#abm_persona").hide();
                //Vuelvo a mostrar los botones ocultos del padre
                $("#btnGuardar").show();
                $("#btnCerrar").show();',
            ]) ?>
        </div>
        <!--?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            ?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']),
                Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']) ?>

    </div-->
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

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
                'tabla_sistema': 'mds_cap_persona',
            },
            function(data) {
                $("#hidden_nueva_persona").val(id_persona);
                $("#nombre_persona").val(data['nombre']);
                $("#apellido_persona").val(data['apellido']);
                $("#nacionalidad_persona").val(data['nacionalidad']);
                $("#sexo_persona").val(data['genero']);

                $("#telefono_persona").val(data['telefono']);
                $("#mail_persona").val(data['mail']);
                $("#fecha_nacimiento").val(FormatearFecha(data['fecha_nacimiento']));
                /* $("#renaper_foto").attr("src", ''); */
                habilitar_controles();
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
                //var sexo = "";
                var nacionalidad = "";
                $.each(data, function(ind, elem) {
                    //nacionalidad = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                    //$("#detalle").val(nacionalidad);//lo plante aca porque era un texto largo
                    console.log(ind);
                    if (ind == 'records') {
                        console.log(elem[0]);
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
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
                $("#nacionalidad_persona").val('');
                $("#sexo_persona").val('');
                /* $("#renaper_foto").attr("src", foto); */
                habilitar_controles();
                $('#txt_mensaje').html("");
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
        $("#sds_800_llamada-telefono").val("");
        $("#sds_800_llamada-domicilio").val("");
        $("#sds_800_llamada-localidad").val("");
        /* $("#renaper_foto").attr("src", ''); */
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

<?php
$script = <<<JS

$('form#{$model->formName()}').on('beforeSubmit',function(e){
    
    var \$form = $(this);
    $.post(
        \$form.attr("action"),
        \$form.serialize()

    )
    .done(function(result){    
        if(result > 0){
            $(\$form).trigger("reset");      
            $('#abm_persona').hide(); 
            e.preventDefault();
            $.post("index.php?r=mds_cap_persona/cmb_contacto", function(data) {
                $("select#cmb_contacto").html(data);
                $("select#cmb_contacto").val(result);                
                //Vuelvo a mostrar los botones ocultos del padre
                $("#btnGuardar").show();
                $("#btnCerrar").show();
                $("#abm_linea").show();
            });          
        }else{
            $("#message").html(result);
        }
    }).fail(function(){
        console.log("server error");
    });
       return false;
})

JS;

$this->registerJs($script);


?>
