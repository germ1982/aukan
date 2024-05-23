<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Sds_com_persona;
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

/* @var $this yii\web\View */
/* @var $model app\models\Mds_inv_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<header class="page-header">
    <h2>Registrar nueva Entrega</h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="mds-atpcen-encuesta-form">
    <div class="row">EN EDICION
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'form_registro',
                    ]); ?>

                    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">

                        INGRESE DNI A REGISTRAR
                        <div class="row">
                            <?php
                            echo $form
                                ->field($model, 'idpersona')
                                ->hiddenInput([
                                    'id' => 'idpersona',
                                    'maxlength' => true,
                                ])
                                ->label(false);
                            if ($model->isNewRecord) {
                                echo '
                    <div class="col-md-3">';
                                echo $form
                                    ->field($model, 'dni_search')
                                    ->textInput([
                                        'id' => 'txtDNI_search',
                                        'maxlength' => true,
                                    ])
                                    ->label('DNI');
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
                            }
                            ?>
                        </div>
                    </div>
                    <br>
                    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:none;" id="divDatosPersonales">
                        DATOS PERSONALES
                        <div class="row">
                            <?php if ($model->isNewRecord) {
                                echo $form
                                    ->field($model, 'idpersona')
                                    ->hiddenInput([
                                        'id' => 'idpersona',
                                        'maxlength' => true,
                                        'style' => 'background-color:#ffffff',
                                    ])
                                    ->label(false);
                                echo '
                

                <div class="row">
                    <div class="col-md-4">';
                                echo $form
                                    ->field($model, 'nombre')
                                    ->textInput([
                                        'id' => 'nombre',
                                        'maxlength' => true,
                                        'readOnly' => false,
                                    ]);
                                echo '</div>   
                    <div class="col-md-4">';
                                echo $form
                                    ->field($model, 'apellido')
                                    ->textInput([
                                        'id' => 'apellido',
                                        'maxlength' => true,
                                        'readOnly' => false,
                                    ]);
                                echo '</div> 
                    
                    <div class="col-md-2"> ';
                                /*$unafecha = explode ("-",$una_com_persona->fecha_nacimiento);
                                $fecha_nacimiento= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);    
                                $una_com_persona->fecha_nacimiento=$fecha_nacimiento;*/

                                echo $form
                                    ->field($model, 'fecha_nac')
                                    ->textInput([
                                        'id' => 'fecha_nac',
                                        'maxlength' => true,
                                        'readOnly' => false,
                                    ]);
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
                                    );

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
                                    );

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
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <?= $form
                                        ->field($model, 'telefono')
                                        ->textInput([
                                            'id' => 'telefono',
                                            'maxlength' => true,
                                            'style' =>
                                                'background-color:#ffffff',
                                        ])
                                        ->label('Telefono') ?>
                                </div>

                            </div>
                        </div> <br>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;display:none;" id="divDatosGenerales">
                            DATOS GENERALES
                            <div class="row">
                                <div class="col-md-2">
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
                                <div class="col-md-2">
                                    <?= $form
                                        ->field($model, 'grupo_familiar')
                                        ->dropDownList(
                                            [  -1 => 'No sabe/No contesta',
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

                            </div>
                        </div> <br>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:none;" id="divTiposAsistencias">
                            TIPOS DE ASISTENCIAS
                            <div class="row">

                                <?php
                                $tipos_asistencias = Sds_com_configuracion::getConfiguraciones(
                                    90
                                );
                                //$inv_asistencias = Mds_inv_asistencia::find()->where(['idpersona' => $model->idrisneu])->all();
                                foreach ($tipos_asistencias as $tipo_asist) {
                                    $checked = '';
                                    /*foreach ($risneu_alims as $ris_alim) {
                                    if ($ris_alim->alimentacion == $tipo_alim->idconfiguracion) {
                                        $checked = "checked";
                                        break;
                                    }
                                }*/

                                    echo '<div class="col-md-2">
                                        <div class="form-group ">' .
                                        '<input type="checkbox" tabindex="1"  name="Sds_ris_risneu[tipo_alim][]"
                                            value=' .
                                        $tipo_asist->idconfiguracion .
                                        ' ' .
                                        $checked .
                                        ' > 
                                            <label>' .
                                        $tipo_asist->descripcion .
                                        '</label>' .
                                        '<div class="help-block"></div>' .
                                        '</div>
                                    </div>';
                                }
                                ?>
                            </div>
                        </div> <br>
                    </div><br>
                    <div class="row" id="footer">
                        <div class="col-md-6">
                            <?php echo Html::button('Cerrar y Volver', [
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
                    <div class="row" id="save_exitoso" style="display:none;">
                        <br>
                        <div class="alert alert-success alert-dismissable" id="save_ok">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4><i class="icon fa fa-check"></i> Registro exitosamente guardado. Registre los plantines.<i class="fas fa-seedling"> </i> <i class="fas fa-heart"></i> <i class="fas fa-seedling"></h4>
                        </div>
                    </div>




                    <br>


                    <?php ActiveForm::end(); ?>
                    <div class="mds-atpcen-encuesta-form">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <section class="panel">
                                    <div class="panel-body">
                                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:none;" id="div_plantines">
                                            ENTREGA DE PLANTINES
                                            <?php
                                            $searchModel2 = new Mds_inv_entregaSearch();
                                            $dataProvider2 = $searchModel2->search(
                                                Yii::$app->request->queryParams
                                            );
                                            ?>

                                            <?php CrudAsset::register($this); ?>
                                            <div class="mds-inv-entrega-index">
                                                <div id="ajaxCrudDatatable">
                                                    <?= GridView::widget([
                                                        'id' =>
                                                            'crud-datatable',
                                                        'dataProvider' => $dataProvider2,
                                                        'filterModel' => $searchModel2,
                                                        'pjax' => true,
                                                        'columns' => require __DIR__ .
                                                            '/_columns2.php',
                                                        'toolbar' => [
                                                            [
                                                                'content' =>
                                                                    Html::a(
                                                                        '<i class="glyphicon glyphicon-plus"></i>',
                                                                        [
                                                                            'createplantin',
                                                                        ],
                                                                        [
                                                                            'id' =>
                                                                                'bot_agregar_plantin',
                                                                            'role' =>
                                                                                'modal-remote',
                                                                            'title' =>
                                                                                'Registrar Nueva Entrega',
                                                                            'class' =>
                                                                                'btn btn-default',
                                                                        ]
                                                                    ) .
                                                                    Html::a(
                                                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                                                        [''],
                                                                        [
                                                                            'data-pjax' => 1,
                                                                            'class' =>
                                                                                'btn btn-default',
                                                                            'title' =>
                                                                                'Reset Grid',
                                                                        ]
                                                                    ) .
                                                                    '{toggleData}' .
                                                                    '{export}',
                                                            ],
                                                        ],
                                                        'striped' => true,
                                                        'condensed' => true,
                                                        'responsive' => true,
                                                        'panel' => [
                                                            'type' => 'default',
                                                            'heading' => '',
                                                            'before' => '',
                                                            'after' =>
                                                                '<div class="clearfix"></div>',
                                                        ],
                                                    ]) ?>
                                                </div>
                                            </div>
                                            <?php Modal::begin([
                                                'id' => 'ajaxCrudModal',
                                                'footer' => '', // always need it for jquery plugin
                                            ]); ?>
                                            <?php Modal::end(); ?>


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
   
    "
    ); ?>

    <script>
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

            dni_search = $("#txtDNI_search").val();
            grupo_familiar = $("#familiar").val();
            telefono = $("#telefono").val();
            email = $("#email").val();
            domicilio = $("#domicilio").val();
            seguimiento = $("#seguimiento").val();

            nacionalidad = $("#nacionalidad").val();
            genero = $("#genero").val();
            fecha_nac = $("#fecha_nac").val();

            nombre = $("#nombre").val();
            apellido = $("#apellido").val();
            $.post("index.php?r=mds_inv_persona/createpersona&apellido=" + apellido + "&nombre=" + nombre + "&fecha_nac=" + fecha_nac + "&genero=" + genero + "&nacionalidad=" + nacionalidad + "&dni=" + dni_search + "&grupo_familiar=" + grupo_familiar +
                "&telefono=" + telefono + "&email=" + email + "&domicilio=" + domicilio + "&seguimiento=" + seguimiento,
                function(data) {

                    if (data != "fallido") {
                        alert(data);
                        // hay que habilitar los plantines                
                        destino = '/mds/web/index.php?r=mds_inv_persona%2Fcreateplantin&idpersona=' + data;
                        $("#bot_agregar_plantin").attr("href", destino);
                        $("#div_plantines").show();
                        $("#save_exitoso").show();
                        $("#boton_guardar").hide();

                        $("#idpersona").val() = data;


                    } else {}
                });
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
                        $('#txt_mensaje').html("lo encontro");
                        $("#nombre").val(data[0]['nombre']);
                        $("#apellido").val(data[0]['apellido']);
                        $("#fecha_nac").val(formatearFecha(data[0]['fecha_nacimiento']));

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
                        /*$("#sds_800_atencion-nacionalidad").val('');
                        $("#sds_800_atencion-sexo").val('');*/
                        //$("#sds_800_atencion-localidad").val(getIdLocalidad(corregir_palabra(localidad)));                       
                        $('#txt_mensaje').html("cargado de renaper");
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