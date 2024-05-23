<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Sds_com_persona;

use kartik\select2\Select2;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use app\models\Mds_inv_entregaSearch;
use app\models\Mds_inv_entrega;
//use yii\grid\GridView;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\bootstrap\Modal;
use kartik\time\TimePicker;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_inv_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-atpcen-encuesta-form">
    <div class="row">

        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php
                    $form = ActiveForm::begin(['id' => 'form_registro']);
                    echo $form->errorSummary($model);
                    ?>

                    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                        INGRESE DNI A REGISTRAR
                        <div class="row">
                            <?php if ($model->isNewRecord) {
                                echo '
                    <div class="col-md-3">';
                                echo $form
                                    ->field($model, 'dni_search')
                                    ->textInput([
                                        'id' => 'txtDNI_search',
                                        'maxlength' => true,
                                    ])
                                    ->label('DNI<sup>*</sup>');
                                echo '<label for="txtDNI_search" style="color: red; display:none" id="labeldni"><sup>El DNI es requerido</sup></label>';
                                echo '</div>';
                                echo '
                    <div class="col-md-3" style="padding-top:25px;">';
                                echo Html::button(
                                    '<i class="glyphicon glyphicon-search"></i>',
                                    [
                                        'class' => 'btn btn-primary btn-flat',
                                        'name' => 'btn_dni',
                                        'id' => 'btn_dni',
                                        'title' => Yii::t('app', 'Buscar DNI'),
                                    ]
                                );
                                echo Html::button('Nueva Busqueda', [
                                    'class' => 'btn btn-success btn-flat',
                                    'style' => 'display: none;',
                                    'id' => 'btnnewsearch',
                                    'title' => Yii::t(
                                        'app',
                                        'Crear Nueva Persona'
                                    ),
                                ]);

                                echo '
                    
                    </div>   
                    <div class="col-md-6" style="padding-top:30px;text-align: left;" id="txt_mensaje">
                            
                    </div>
                    <div class="col-md-1" style="text-align: right;">';
                                //echo $form->field($model, 'idpersonacap')->hiddenInput(['id' => 'idpersonacap'])->label(false) ;
                                echo '                            
                    </div>                     
                    ';
                            } else {
                                /*$cap = Mds_cap_persona::findOne($model->idpersonacap);
                    $per = Sds_com_persona::findOne($cap['idpersona']);                                                
                    $model->nombre_apell=$per['nombre'] . " " . $per['apellido'];
                    $model->el_dni=$per['documento'];
                    echo '<div class="col-md-6">';
                    echo  $form->field($model, 'nombre_apell')->textInput(['maxlength' => true,"readOnly"=>true])->label("Nombres y apellidos") ;                        
                    echo '</div>';
                    echo '<div class="col-md-6">';                        
                    echo  $form->field($model, 'el_dni')->textInput(['maxlength' => true,"readOnly"=>true])->label("DNI") ;
                    echo '</div>';*/
                            } ?>
                        </div>
                    </div>
                    <br>
                    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:none;" id="divDatosPersonales">
                        DATOS PERSONALES
                        <div class="row">
                            <?php if ($model->isNewRecord) {
                                echo '
                <div class="col-md-12">  

                <div class="row">
                    <div class="col-md-4">';
                                echo $form
                                    ->field($model, 'nombre')
                                    ->textInput([
                                        'id' => 'nombre',
                                        'maxlength' => true,
                                        'readOnly' => false,
                                    ])
                                    ->label('Nombre<sup>*</sup>');
                                echo '<label for="nombre" style="color: red; display:none" id="labelnombre"><sup>El nombre es requerido</sup></label>';
                                echo '</div>   
                    <div class="col-md-4">';
                                echo $form
                                    ->field($model, 'apellido')
                                    ->textInput([
                                        'id' => 'apellido',
                                        'maxlength' => true,
                                        'readOnly' => false,
                                    ])
                                    ->label('Apellido<sup>*</sup>');
                                echo '<label for="apellido" style="color: red; display:none" id="labelapellido"><sup>El apellido es requerido</sup></label>';
                                echo '</div> 
                    
                    <div class="col-md-4"> ';
                                /*$unafecha = explode ("-",$una_com_persona->fecha_nacimiento);
                                $fecha_nacimiento= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);    
                                $una_com_persona->fecha_nacimiento=$fecha_nacimiento;*/
                                if ($model->fecha_nac != null) {
                                    $model->fecha_nac = date(
                                        'd/m/Y',
                                        strtotime(
                                            str_replace(
                                                '/',
                                                '-',
                                                $model->fecha_nac
                                            )
                                        )
                                    );
                                }
                                //echo $form->field($model, 'fecha_nac')->textInput(['id'=>'fecha_nac','maxlength' => true,"readOnly"=>false]);
                                echo $form
                                    ->field($model, 'fecha_nac')
                                    ->widget(DatePicker::ClassName(), [
                                        'language' => 'es',
                                        'readonly' => false,
                                        // 'layout' => '{picker}{input}{remove}',
                                        'layout' => !$model->isNewRecord
                                            ? '{picker}{input}'
                                            : '{picker}{input}',
                                        'options' => [
                                            'id' => 'fecha_nac',
                                        ],

                                        'pluginOptions' => [
                                            'value' => null,
                                            'format' => 'dd/mm/yyyy',
                                            //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                        ],
                                    ])
                                    ->label('Fecha de Nacimiento');
                                echo '</div>                      
                </div>

                <div class="row">                    
                    <div class="col-md-2">';
                                echo $form
                                    ->field($model, 'id_genero')
                                    ->dropDownList(
                                        ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguraciones(
                                                Sds_com_configuracion_tipo::TIPO_GENERO
                                            ),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        //['prompt' => 'Seleccionar Sexo ...', 'disabled' => true]
                                        [
                                            'prompt' => 'Sel. Genero ...',
                                            'disabled' => false,
                                            'id' => 'genero',
                                        ]
                                    )
                                    ->label('Genero<sup>*</sup>');
                                echo '<label for="genero" style="color: red; display:none" id="labelgenero"><sup>El genero es requerido</sup></label>';
                                echo '
                    </div>  
                    <div class="col-md-2">';
                                echo $form
                                    ->field($model, 'id_nacionalidad')
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
                                            'prompt' => 'Sel. Nacionalidad ...',
                                            'id' => 'nacionalidad',
                                        ]
                                    )
                                    ->label('Nacionalidad<sup>*</sup>');
                                echo '<label for="nacionalidad" style="color: red; display:none" id="labelnacionalidad"><sup>La nacionalidad es requerida</sup></label>';
                                echo '
                    </div>             
                    <div class="col-md-8"> ';
                                echo $form
                                    ->field($model, 'domicilio')
                                    ->textInput([
                                        'id' => 'domicilio',
                                        'maxlength' => true,
                                        'readOnly' => false,
                                        'style' => 'background-color:#ffffff',
                                    ]);
                                echo '          
                    </div>                     
                </div>                 
            </div>  ';
                            } ?>
                        </div>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:none;" id="divDatosContacto">
                            CONTACTO
                            <div class="row">
                                <div class="col-md-5">
                                    <?= $form
                                        ->field($model, 'email')
                                        ->textInput([
                                            'id' => 'email',
                                            'maxlength' => true,
                                            'style' =>
                                                'background-color:#ffffff',
                                        ])
                                        ->label('Email') ?>
                                </div>
                                <div class="col-md-5">
                                    <?= $form
                                        ->field($model, 'telefono')
                                        ->textInput([
                                            'id' => 'telefono',
                                            'maxlength' => true,
                                            'style' =>
                                                'background-color:#ffffff',
                                        ])
                                        ->label('Telefono') ?>
                                    <?= $form
                                        ->field($model, 'whatsapp')
                                        ->checkBox([
                                            'id' => 'whatsapp',
                                            'label' => 'Tiene Whatsapp',
                                            'uncheck' => null,
                                        ]) ?>

                                </div>

                            </div>
                        </div> <br>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;display:none;" id="divDatosGenerales">
                            DATOS GENERALES
                            <div class="row">
                                <div class="col-md-3">
                                    <?= $form
                                        ->field($model, 'seguimiento')
                                        ->dropDownList(
                                            [
                                                1 => 'Si',
                                                0 => 'No',
                                            ],
                                            ['id' => 'seguimiento'],
                                            [
                                                'prompt' =>
                                                    '-- Seleccione una opción --',
                                            ]
                                        ) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form
                                        ->field($model, 'grupo_familiar')
                                        ->dropDownList(
                                            [
                                                -1 => 'No sabe/No contesta',
                                                0 => '0',
                                                1 => '1',
                                                2 => '2',
                                                3 => '3',
                                                4 => '4',
                                                5 => '5',
                                                6 => '6',
                                                7 => '7',
                                                8 => '8',
                                                9 => '9',
                                                10 => '10',
                                                11 => '11',
                                                12 => '12',
                                                13 => '13',
                                                14 => '14',
                                                15 => '15',
                                                16 => '16',
                                                17 => '17',
                                                18 => '18',
                                                19 => '19',
                                                20 => '20',
                                                21 => '21',
                                                22 => '22',
                                                23 => '23',
                                            ],
                                            ['id' => 'familiar'],
                                            [
                                                'prompt' =>
                                                    '-- Seleccione una opción --',
                                            ]
                                        ) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form
                                        ->field($model, 'cant_nnya')
                                        ->dropDownList(
                                            [
                                                -1 => 'No sabe/No contesta',
                                                0 => '0',
                                                1 => '1',
                                                2 => '2',
                                                3 => '3',
                                                4 => '4',
                                                5 => '5',
                                                6 => '6',
                                                7 => '7',
                                                8 => '8',
                                                9 => '9',
                                                10 => '10',
                                                11 => '11',
                                                12 => '12',
                                                13 => '13',
                                                14 => '14',
                                                15 => '15',
                                                16 => '16',
                                                17 => '17',
                                                18 => '18',
                                                19 => '19',
                                                20 => '20',
                                                21 => '21',
                                                22 => '22',
                                                23 => '23',
                                            ],
                                            ['id' => 'cant_nnya'],
                                            [
                                                'prompt' =>
                                                    '-- Seleccione una opción --',
                                            ]
                                        ) 
                                        ->label('¿Cuántos NNy/oA hay en la familia?')
                                        
                                        ?>
                                </div>


                            </div>
                        </div> <br>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:none;" id="divTiposAsistencias">
                            TIPOS DE ASISTENCIAS
                            <?php
                            $tipos_asistencias = Sds_com_configuracion::getConfiguraciones(
                                90
                            );
                            //$inv_asistencias = Mds_inv_asistencia::find()->where(['idpersona' => $model->idrisneu])->all();
                            $cont = 0;
                            $cad_asistencias1 = '';
                            $cad_asistencias2 = '';
                            foreach ($tipos_asistencias as $tipo_asist) {
                                $checked = '';
                                /*foreach ($risneu_alims as $ris_alim) {
                                    if ($ris_alim->alimentacion == $tipo_alim->idconfiguracion) {
                                        $checked = "checked";
                                        break;
                                    }
                                }*/

                                if (
                                    $tipo_asist->idconfiguracion == 2188 ||
                                    $tipo_asist->idconfiguracion == 2187
                                ) {
                                    $cad_asistencias2 =
                                        $cad_asistencias2 .
                                        '
                                    <div class="col-md-3"> ' .
                                        '<input type="checkbox" tabindex="1"  id="asistencia' .
                                        $cont .
                                        '"' .
                                        'value=' .
                                        $tipo_asist->idconfiguracion .
                                        ' ' .
                                        $checked .
                                        ' > ' .
                                        $tipo_asist->descripcion;
                                    if ($tipo_asist->idconfiguracion == 2187) {
                                        $cad_asistencias2 =
                                            $cad_asistencias2 .
                                            $form
                                                ->field($model, 'obs1')
                                                ->textInput([
                                                    'id' => 'obs1',
                                                    'readOnly' => false,
                                                ])
                                                ->label(false);
                                    } else {
                                        if (
                                            $tipo_asist->idconfiguracion == 2188
                                        ) {
                                            $cad_asistencias2 =
                                                $cad_asistencias2 .
                                                $form
                                                    ->field($model, 'obs2')
                                                    ->textInput([
                                                        'id' => 'obs2',
                                                        'readOnly' => false,
                                                    ])
                                                    ->label(false);
                                        }
                                    }
                                    $cad_asistencias2 =
                                        $cad_asistencias2 . '</div>';
                                } else {
                                    $cad_asistencias1 =
                                        $cad_asistencias1 .
                                        '
                                     <div class="col-md-3"> ' .
                                        '<input type="checkbox" tabindex="1"  id="asistencia' .
                                        $cont .
                                        '"' .
                                        ' value=' .
                                        $tipo_asist->idconfiguracion .
                                        ' ' .
                                        $checked .
                                        ' > ' .
                                        $tipo_asist->descripcion;

                                    $cad_asistencias1 =
                                        $cad_asistencias1 . '</div>';
                                }
                                $cont++;
                            }

                            if ($cad_asistencias1 != '') {
                                echo '<div class="row">';
                                echo $cad_asistencias1;
                                echo '</div>';
                            }
                            if ($cad_asistencias2 != '') {
                                echo '<div class="row">';
                                echo $cad_asistencias2;
                                echo '</div>';
                            }
                            $model->num_opciones_asistencia = $cont;
                            echo $form
                                ->field($model, 'num_opciones_asistencia')
                                ->hiddenInput([
                                    'id' => 'num_opciones_asistencia',
                                ])
                                ->label(false);
                            ?>

                            <div class="row">
                                <div class="col-md-4" id="div_recibe_plantines">
                                    <?= $form
                                        ->field($model, 'recibe_plantines')
                                        ->dropDownList(
                                            [
                                                0 => 'No contesta',
                                                1 => 'No sabe',
                                                2 => 'Si',
                                                3 => 'No',
                                            ],
                                            ['id' => 'recibe_plantines'],
                                            [
                                                'prompt' =>
                                                    '-- Seleccione una opción --',
                                            ]
                                        )  
                                        ->label('¿Recibió plantines anteriormente?')?>
                                </div>
                                <div class="col-md-4" id="div_cosecha_plantines" style="display:none;">
                                    <?= $form
                                        ->field($model, 'cosecha_plantines')
                                        ->dropDownList(
                                            [
                                                0 => 'No contesta',
                                                1 => 'No sabe',
                                                2 => 'Si',
                                                3 => 'No',
                                            ],
                                            ['id' => 'cosecha_plantines'],
                                            [
                                                'prompt' =>
                                                    '-- Seleccione una opción --',
                                            ]
                                        ) 
                                        ->label('¿Cosechó plantines?')?>
                                </div>
                            </div>
                        </div>
                    </div> <br>
                </div><br>

                <div class="row" id="save_exitoso" style="display:none;">
                    <br>
                    <div class="alert alert-success alert-dismissable" id="save_ok">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i> Registro exitosamente guardado. Haga click en "Cerrar" y registre los plantines.
                            <i class="fas fa-seedling"></i> <i class="fas fa-heart"></i> <i class="fas fa-seedling"></i>
                        </h4>
                    </div>
                </div>
                <div class="row" id="save_existe" style="display:none;">
                    <br>
                    <div class="alert alert-danger alert-dismissable" id="save_ok">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i> La persona con el dni ingresado ya esta registrado. Intente con otro dni.
                            <i class="fas fa-seedling"></i> <i class="fas fa-heart"></i> <i class="fas fa-seedling"></i>
                        </h4>
                    </div>
                </div>
                <br>


                <?php ActiveForm::end(); ?>

                <?php Modal::begin([
                    'id' => 'ajaxCrudModal',
                    'footer' => '', // always need it for jquery plugin
                ]); ?>
                <?php Modal::end(); ?>


                <div class="row" id="footer">
                    <div class="col-md-6">
                        <?php echo Html::button('Cerrar', [
                            'class' => 'btn btn-info btn-flat float-right',
                            'id' => 'boton_cerrar',
                            'data-dismiss' => 'modal',
                        ]); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo Html::button('Guardar Registro', [
                            'class' => 'btn btn-primary btn-flat',
                            'id' => 'boton_guardar',
                            'style' => 'display: none;',
                        ]); ?>
                    </div>

                </div>


        </div>
    </div>
</div>
</section>
</div>


</div>

<?php $this->registerJs(
    "
    
    $('#btn_dni').click(function(){                
        datos_de_la_persona();
    });
    $('#btnnewsearch').click(function(){                
        reg_new_search();
    });
    $('#txtDNI_search').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
    });
    $('#boton_guardar').click(function(){                
        guardar_inscripcion();
    });
    $('#nueva_entrega8').click(function(){                
        nueva_entrega();
    });
    $('#recibe_plantines').change(function(){                
        verificar_respuesta();
    });
   
    
    "
); ?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    function verificar_respuesta() {
        //div_recibe_plantines    div_cosecha_plantines
        recibe_plantines = $('#recibe_plantines').val();
        if(recibe_plantines==2)
        {
            $("#div_cosecha_plantines").show();
        }
        else
        {
            $("#div_cosecha_plantines").hide();
        }
        
        return false;
    }
    function agregar_plantin() {
        alert('vamos a agregar el plantin');
        return false;
    }

    function nueva_entrega() {

        idpersona = $('#idpersona').val();
        $.post("index.php?r=mds_inv_persona/createplantin&idpersona=" + idpersona, function(data) {


        });
    }


    function guardar_inscripcion() {
        $("#labeldni").hide();
        $("#labelnombre").hide();
        $("#labelapellido").hide();
        $("#labelgenero").hide();
        $("#labelnacionalidad").hide();
        dni_search = $("#txtDNI_search").val();
        grupo_familiar = $("#familiar").val();
        telefono = $("#telefono").val();
        email = $("#email").val();
        domicilio = $("#domicilio").val();
        seguimiento = $("#seguimiento").val();


        cant_nnya = $("#cant_nnya").val();
        recibe_plantines = $("#recibe_plantines").val();
        cosecha_plantines = $("#cosecha_plantines").val();

        nacionalidad = $("#nacionalidad").val();
        genero = $("#genero").val();
        fecha_nac = $("#fecha_nac").val();
        if ($('#whatsapp').prop('checked')) {
            whatsapp = 1;
        } else {
            whatsapp = 0;
        }


        nombre = $("#nombre").val();
        apellido = $("#apellido").val();

        var error = false;
        var cad_error = '';
        if (dni_search.trim() == '') {
            error = true;
            cad_error = "Error: DNI no debe estar vacío. Campo obligatorio.";
            $("#labeldni").show();
        } else {
            if (nombre.trim() == '') {
                error = true;
                cad_error = "Error: Nombre no debe estar vacío. Campo obligatorio.";
                $("#labelnombre").show();
            } else {
                if (apellido.trim() == '') {
                    error = true;
                    cad_error = "Error: Apellido no debe estar vacío. Campo obligatorio.";
                    $("#labelapellido").show();
                } else {
                    if (genero.trim() == '') {
                        error = true;
                        cad_error = "Error: Debe seleccionar genero. Campo obligatorio.";
                        $("#labelgenero").show();
                    } else {
                        if (nacionalidad.trim() == '') {
                            error = true;
                            cad_error = "Error: Debe seleccionar genero. Campo obligatorio.";
                            $("#labelnacionalidad").show();
                        }
                    }
                }
            }
        }





        if (error == false) {

            num_opciones_asistencia = $("#num_opciones_asistencia").val();
            var arr_seg = [];
            var arr_seg2 = [];
            var j = 0;
            arr_seg2[0] = null;
            arr_seg2[1] = null;
            for (var i = 0; i < num_opciones_asistencia; i++) {
                cad = i.toString();
                cadfinal = "#asistencia" + cad;
                if ($(cadfinal).prop('checked')) {
                    //($tipo_asist->idconfiguracion==2188) || ($tipo_asist->idconfiguracion==2187))
                    if ($(cadfinal).val() == 2187) {
                        //obs1
                        arr_seg2[0] = $("#obs1").val();

                    } else {
                        if ($(cadfinal).val() == 2188) {
                            arr_seg2[1] = $("#obs2").val();

                        }
                    }

                    arr_seg[j] = $(cadfinal).val();
                    j++;
                }

            }
            $.post("index.php?r=mds_inv_persona/createpersona&whatsapp=" + whatsapp + "&arr_seg2=" + arr_seg2 + "&arr_seg=" + arr_seg + "&apellido=" + apellido + "&nombre=" + nombre + "&fecha_nac=" + fecha_nac + "&genero=" + genero + "&nacionalidad=" + nacionalidad + "&dni=" + dni_search + "&grupo_familiar=" + grupo_familiar +
                "&telefono=" + telefono + "&email=" + email + "&domicilio=" + domicilio + "&seguimiento=" + seguimiento+ "&cant_nnya=" + cant_nnya + "&recibe_plantines=" + recibe_plantines + "&cosecha_plantines=" + cosecha_plantines ,
                function(data) {
                    if (data != "fallido") {

                        if (data == "yaexiste") {
                            $("#save_existe").show();
                        } else {
                            $("#save_exitoso").show();
                            $("#boton_guardar").hide();
                            $("#btnnewsearch").hide();
                        }
                    } else {}
                });



        }
        /*else
        {
            swal("No se puede guardar el registro nuevo!", cad_error);
        }*/


    }

    function reg_new_search() {
        $("#txtDNI_search").removeAttr('readonly');
        $("#divDatosPersonales").hide();
        $("#btnnewsearch").hide();
        $("#divDatosContacto").hide();
        $("#divDatosGenerales").hide();
        $("#divTiposAsistencias").hide();
        $("#boton_guardar").hide();
        $('#form_registro')[0].reset();
        $('#txt_mensaje').html("");
        $("#btn_dni").show();

    }

    function datos_de_la_persona() {
        //$("#div_duplicados").hide();       
        var dni_campo = $('#txtDNI_search').val();
        if (dni_campo != '') {
            $('#txt_mensaje').html("Buscando datos de la Persona...");
            dni = dni_campo;
            $.post("index.php?r=sds_com_persona/validar_dni&dni=" + dni, function(data) {

                data = $.parseJSON(data);
                if (data.length == 0) {
                    datos_renaper(dni);
                    $.post("index.php?r=mds_inv_persona/entrega&dni=" + dni, function(data)
                    {    console.log("RENAPER: "+data);                         
                        if (data=='["si"]'){
                        
                            document.getElementById("recibe_plantines").value = "2"; 
                            document.getElementById("cosecha_plantines").value = "0"; 
                            $("#div_cosecha_plantines").show();
                         }
                         else
                         {
                            
                            document.getElementById("recibe_plantines").value = "0";
                            document.getElementById("cosecha_plantines").value = "0"; 
                            $("#div_cosecha_plantines").hide(); 
                         }

                    });
                    $("#divDatosPersonales").show();
                    $("#btnnewsearch").show();
                    $("#divDatosContacto").show();
                    $("#divDatosGenerales").show();
                    $("#divTiposAsistencias").show();
                    $("#txtDNI_search").attr("readonly", "readonly");
                    $('#txt_mensaje').html("Persona Encontrada");
                    $("#boton_guardar").show();
                    $("#btn_dni").hide();

                } else {

                    //Evaluamos si ya se entrego plantines a la persona buscada:
                    $.post("index.php?r=mds_inv_persona/entrega&dni=" + dni, function(data)
                    {    console.log(typeof data)
                        console.log("EN SUR: "+data);                        
                        if (data=='["si"]'){
                            
                            document.getElementById("recibe_plantines").value = "2"; 
                            document.getElementById("cosecha_plantines").value = "0"; 
                            $("#div_cosecha_plantines").show();
                        }
                        else
                         {
                            document.getElementById("recibe_plantines").value = "0"; 
                            document.getElementById("cosecha_plantines").value = "0"; 
                            $("#div_cosecha_plantines").hide();
                         }
                        

                    });
                    $('#txt_mensaje').html("lo encontro");
                    $("#nombre").val(data[0]['nombre']);
                    $("#apellido").val(data[0]['apellido']);
                    $("#fecha_nac").val(formatearFecha(data[0]['fecha_nacimiento']));
                    $("#genero").val(data[0]['genero']);


                    $("#divDatosPersonales").show();
                    $("#btnnewsearch").show();
                    $("#divDatosContacto").show();
                    $("#divDatosGenerales").show();
                    $("#divTiposAsistencias").show();
                    $("#txtDNI_search").attr("readonly", "readonly");
                    $('#txt_mensaje').html("Persona Encontrada");
                    $("#boton_guardar").show();
                    $("#btn_dni").hide();


                }
            });
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
                var localidad = "";
                var foto = "";
                var fecha_nacimiento = null;
                var el_domicilio = "";
                var calle = "";
                var monoblock = "";
                var numero = "";
                var provincia = "";
                var genero = "";

                $.each(data, function(ind, elem) {
                    console.log(ind);
                    if (ind == 'records') {
                        console.log(elem[0]);
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
                        localidad = elem[0].result.ciudad;
                        calle = elem[0].result.calle;
                        monoblock = elem[0].result.monoblock;
                        numero = elem[0].result.numero;
                        provincia = elem[0].result.provincia;
                        barrio = elem[0].result.barrio;
                        //foto = elem[0].result.foto;
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                        if (elem[0].result.genero == 'F') {
                            genero = 81;
                        } else {
                            if (elem[0].result.genero == 'M') {
                                genero = 82;
                            } else {
                                genero = 688;
                            }
                        }


                        if (calle != '') {
                            el_domicilio = "calle " + corregir_palabra(calle);
                        } else {
                            el_domicilio = "calle s/n ";
                        }
                        if (numero != '') {
                            el_domicilio = el_domicilio + ", numero " + corregir_palabra(numero);
                        } else {
                            el_domicilio = el_domicilio + ", sin numero";
                        }
                        if (provincia != '') {
                            el_domicilio = el_domicilio + ", provincia de " + corregir_palabra(provincia);
                        } else {
                            el_domicilio = el_domicilio + ", provincia desconocida ";
                        }
                        if (barrio != '') {
                            el_domicilio = el_domicilio + ", barrio " + corregir_palabra(barrio);
                        } else {
                            el_domicilio = el_domicilio + ", barrio s/n";
                        }
                    }
                });
                if (fecha_nacimiento != null) {

                    $("#nombre").val(corregir_palabra(nombre));
                    $("#apellido").val(corregir_palabra(apellido));
                    $("#fecha_nac").val(fecha_nacimiento);
                    $("#domicilio").val(el_domicilio);

                    $("#genero").val(genero);
                    /*$("#sds_800_atencion-nacionalidad").val('');
                    $("#sds_800_atencion-sexo").val('');*/
                    //$("#sds_800_atencion-localidad").val(getIdLocalidad(corregir_palabra(localidad)));                       
                    //$('#txt_mensaje').html("cargado de renaper");                        
                }
            }
        });
    }

    function limpiarDatos() {
        $("#mds_inv_persona-nombre").val('');
        $("#mds_inv_persona-apellido").val('');
        $("#mds_inv_persona-fecha_nac").val('');
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