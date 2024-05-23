<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Sds_com_persona;
use app\models\Mds_inv_asistencia;
use app\models\Sds_gis_capa_item;

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

$un_com_persona = Sds_com_persona::find()
    ->where(['idpersona' => $model->idpersona])
    ->one();
?>

<div class="mds-atpcen-encuesta-form">
    <div class="row">

        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'form_registro',
                    ]); ?>

                    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:block;" id="divDatosPersonales">
                        DATOS PERSONALES
                        <div class="row">
                            <div class="col-md-4">
                                <?php $model->dni_search =
                                    $un_com_persona->documento; ?>
                                <?= $form
                                    ->field($model, 'dni_search')
                                    ->textInput([
                                        'id' => 'txtDNI_search',
                                        'maxlength' => true,
                                        'readOnly' => true,
                                        'style' => 'background-color:#ffffff',
                                    ])
                                    ->label('DNI') ?>

                            </div>
                        </div>
                        <div class="row">
                            <?php
                            echo '
                <div class="col-md-12">  

                <div class="row">
                    <div class="col-md-4">';
                            $model->nombre = $un_com_persona->nombre;
                            echo $form->field($model, 'nombre')->textInput([
                                'id' => 'nombre',
                                'maxlength' => true,
                                'readOnly' => true,
                                'style' => 'background-color:#ffffff',
                            ]);
                            echo '</div>   
                    <div class="col-md-4">';
                            $model->apellido = $un_com_persona->apellido;
                            echo $form->field($model, 'apellido')->textInput([
                                'id' => 'apellido',
                                'maxlength' => true,
                                'readOnly' => true,
                                'style' => 'background-color:#ffffff',
                            ]);
                            echo '</div> 
                    
                    <div class="col-md-4"> ';

                            $unafecha = explode(
                                '-',
                                $un_com_persona->fecha_nacimiento
                            );

                            $fecha_nacimiento =
                                trim($unafecha[2]) .
                                '/' .
                                trim($unafecha[1]) .
                                '/' .
                                trim($unafecha[0]);
                            $un_com_persona->fecha_nacimiento = $fecha_nacimiento;

                            $model->fecha_nac =
                                $un_com_persona->fecha_nacimiento;
                            echo $form
                                ->field($model, 'fecha_nac')
                                ->textInput([
                                    'id' => 'fecha_nac',
                                    'maxlength' => true,
                                    'readOnly' => true,
                                    'style' => 'background-color:#ffffff',
                                ])
                                ->label('Fecha de Nacimiento');

                            echo '</div>                      
                </div>

                <div class="row">                    
                    <div class="col-md-3">';

                            $model->id_genero = $un_com_persona->genero;
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
                                        'disabled' => true,
                                        'id' => 'genero',
                                        'style' => 'background-color:#ffffff',
                                    ]
                                );

                            echo '
                    </div>  
                    <div class="col-md-3">';
                            $model->id_nacionalidad =
                                $un_com_persona->nacionalidad;
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
                                        'disabled' => true,
                                        'id' => 'nacionalidad',
                                        'style' => 'background-color:#ffffff',
                                    ]
                                );

                            echo '
                    </div>             
                    <div class="col-md-6"> ';
                            echo $form->field($model, 'domicilio')->textInput([
                                'id' => 'domicilio',
                                'maxlength' => true,
                                'readOnly' => true,
                                'style' => 'background-color:#ffffff',
                            ]);
                            echo '          
                    </div>                     
                </div>                 
            </div>  ';
                            ?>
                        </div>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:block;" id="divDatosContacto">
                            CONTACTO
                            <div class="row">
                                <div class="col-md-5">
                                    <?= $form
                                        ->field($model, 'email')
                                        ->textInput([
                                            'id' => 'email',
                                            'maxlength' => true,
                                            'readOnly' => true,
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
                                            'readOnly' => true,
                                            'style' =>
                                                'background-color:#ffffff',
                                        ])
                                        ->label('Telefono') ?>
                                    <?php if ($model->whatsapp) {
                                        echo 'Tiene Whatsapp';
                                    } else {
                                        echo 'No Tiene Whatsapp';
                                    } ?>
                                </div>

                            </div>
                        </div> <br>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;display:block;" id="divDatosGenerales">
                            DATOS GENERALES
                            <div class="row">
                                <div class="col-md-3">
                                    <?php if ($model->seguimiento == 1) {
                                        echo 'Requiere Seguimiento: Si';
                                    } else {
                                        echo 'Requiere Seguimiento: No';
                                    } ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo 'Grupo Familiar: ' .
                                        $model->grupo_familiar; ?>
                                </div>
                                <div class="col-md-5">
                                    <?php echo 'Cantidad de NNy/oA que hay en la familia: ';
                                    if ($model->cant_nnya==-1){echo 'No sabe/No contesta';} 
                                    else {echo $model->cant_nnya; }
                                        ?>
                                        
                                </div>

                            </div>
                        </div> <br>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:block;" id="divTiposAsistencias">
                            TIPOS DE ASISTENCIAS
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $cadena = '';
                                    $asistencias = Mds_inv_asistencia::findBySql(
                                        "SELECT *
                            FROM mds_inv_asistencia                                                
                            WHERE idpersona=" . $model->idpersona
                                    )->all();
                                    foreach ($asistencias as $una_asistencia) {
                                        $una_conf_asistencia = Sds_com_configuracion::find()
                                            ->where([
                                                'idconfiguracion' =>
                                                    $una_asistencia->idconfiguracion,
                                            ])
                                            ->one();

                                        if (
                                            $una_conf_asistencia->idconfiguracion ==
                                                2188 ||
                                            $una_conf_asistencia->idconfiguracion ==
                                                2187
                                        ) {
                                            if ($cadena != '') {
                                                $cadena .=
                                                    ' / ' .
                                                    $una_conf_asistencia->descripcion .
                                                    ' (' .
                                                    $una_asistencia->descripcion .
                                                    ')';
                                            } else {
                                                $cadena .=
                                                    $una_conf_asistencia->descripcion .
                                                    ' (' .
                                                    $una_asistencia->descripcion .
                                                    ')';
                                            }
                                        } else {
                                            if ($cadena != '') {
                                                $cadena .=
                                                    ' / ' .
                                                    $una_conf_asistencia->descripcion;
                                            } else {
                                                $cadena .=
                                                    $una_conf_asistencia->descripcion;
                                            }
                                        }
                                    }
                                    echo $cadena;
                                    ?>
                                </div>
                            
                                <div class="col-md-5">
                                  <?php echo '¿Recibió plantines anteriormente?: ';?>
                                    <?php 
                                    if ($model->recibe_plantines == 0) {
                                        echo 'No contesta';
                                    } 
                                    else
                                    {
                                        if ($model->recibe_plantines == 1) {
                                            echo 'No sabe';
                                        } 
                                        else
                                        {
                                            if ($model->recibe_plantines == 2) {
                                                echo 'Si';
                                            } 
                                            else
                                            if ($model->recibe_plantines == 3) {
                                                echo 'No';
                                            } 
                                        }

                                    }                                                                    
                                    
                                    ?>
                                </div>
                                <div class="col-md-6" style="display: <?= $model->recibe_plantines == 2 ? 'block' : 'none'   ?>">
                                  <?php echo '¿Cosechó plantines?: ';?>
                                    <?php 
                                    if ($model->cosecha_plantines == 0) {
                                        echo 'No contesta';
                                    } 
                                    else
                                    {
                                        if ($model->cosecha_plantines == 1) {
                                            echo 'No sabe';
                                        } 
                                        else
                                        {
                                            if ($model->cosecha_plantines == 2) {
                                                echo 'Si';
                                            } 
                                            else
                                            if ($model->cosecha_plantines == 3) {
                                                echo 'No';
                                            } 
                                        }

                                    }                                                                    
                                    
                                    ?>
                                </div>

                            </div>
                        </div> <br>


                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:block;" id="divTiposAsistencias">
                            PLANTINES
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $las_entregas = Mds_inv_entrega::findBySql(
                                        "SELECT *
                    FROM mds_inv_entrega                                                
                    WHERE idpersona=" . $model->idpersona
                                    )->all();

                                    $i = 1;

                                    echo '<table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Especie</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Fecha Entrega</th>
                    <th scope="col">Lugar Entrega</th>
                    </tr>
                </thead>
                <tbody>';
                                    foreach ($las_entregas as $una_entrega) {
                                        if (
                                            $una_entrega->fecha_entrega == null
                                        ) {
                                            $fechahora_entrega = 'no definida';
                                        } else {
                                            $unafechahora = explode(
                                                ' ',
                                                $una_entrega->fecha_entrega
                                            );
                                            $la_fecha = $unafechahora[0];
                                            $la_hora = $unafechahora[1];

                                            $unafecha = explode('-', $la_fecha);

                                            $fecha_entrega =
                                                trim($unafecha[2]) .
                                                '/' .
                                                trim($unafecha[1]) .
                                                '/' .
                                                trim($unafecha[0]);
                                            $fechahora_entrega = $fecha_entrega;
                                        }

                                        $el_lugar = Sds_gis_capa_item::find()
                                            ->where([
                                                'idcapaitem' =>
                                                    $una_entrega->idlugar,
                                            ])
                                            ->one();

                                        $una_conf_especie = Sds_com_configuracion::find()
                                            ->where([
                                                'idconfiguracion' =>
                                                    $una_entrega->idespecie,
                                            ])
                                            ->one();
                                        echo '<tr>
                    <th scope="row">' .
                                            $i .
                                            '</th>
                    <td>' .
                                            $una_conf_especie->descripcion .
                                            '</td>
                    <td>' .
                                            $una_entrega->cantidad .
                                            '</td>
                    <td>' .
                                            $fechahora_entrega .
                                            '</td>
                    <td>' .
                                            $el_lugar->descripcion .
                                            '</td>
                    </tr>';
                                        $i++;
                                    }

                                    echo '</tbody>
                </table>';
                                    ?>



                                </div>
                            </div>
                        </div><br>

                        <div class="row" id="save_exitoso" style="display:none;">
                            <br>
                            <div class="alert alert-success alert-dismissable" id="save_ok">
                                <?= $form
                                    ->field($model, 'idpersona')
                                    ->hiddenInput([
                                        'id' => 'idpersona',
                                        'maxlength' => true,
                                        'readOnly' => true,
                                    ])
                                    ->label(false) ?>
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <h4><i class="icon fa fa-check"></i> Cambios exitosamente guardados. Haga click en "Cerrar" y registre los plantines.<i class="fas fa-seedling"></i> <i class="fas fa-heart"></i> <i class="fas fa-seedling"></i></h4>
                            </div>
                        </div>
                        <br>

                        <div class="row" id="footer">
                            <div class="col-md-6">
                                <?php echo Html::button('Cerrar', [
                                    'class' =>
                                        'btn btn-info btn-flat float-right',
                                    'id' => 'boton_cerrar',
                                    'data-dismiss' => 'modal',
                                ]); ?>
                            </div>

                        </div>

                        <?php ActiveForm::end(); ?>

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
    $('#boton_guardar50').click(function(){                
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
        idpersona = $("#idpersona").val();
        $.post("index.php?r=mds_inv_persona/updatepersona&idpersona=" + idpersona + "&apellido=" + apellido + "&nombre=" + nombre + "&fecha_nac=" + fecha_nac + "&genero=" + genero + "&nacionalidad=" + nacionalidad + "&dni=" + dni_search + "&grupo_familiar=" + grupo_familiar +
            "&telefono=" + telefono + "&email=" + email + "&domicilio=" + domicilio + "&seguimiento=" + seguimiento,
            function(data) {

            });
        $("#boton_guardar50").hide();
        $("#save_exitoso").show();
        $("#btnnewsearch").hide();



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