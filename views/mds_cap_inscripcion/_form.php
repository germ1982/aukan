<?php

use app\models\Mds_cap_inscripcion;
use app\models\Mds_cap_instancia;
use app\models\Mds_cap_persona;
use app\models\Mds_cap_capacitacion;

use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_inscripcion */
/* @var $form yii\widgets\ActiveForm */

$idcontacto  = Yii::$app->user->identity->idcontacto;
$idusuario = Yii::$app->user->identity->idusuario;
$permiso_global = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GLOBAL . ")")->one();
$permiso_global = $permiso_global != null ? 1 : 0;

?>

<div class="mds-cap-inscripcion-form" id="form_completo">

    <?php $form = ActiveForm::begin(["id"=>'form_inscripcion']); ?> 
    <div class="alert alert-success alert-dismissable" id="save_ok" style="display:none;">         
         <h4><i class="icon fa fa-check"></i> La inscripción fue guardada exitosamente</h4>         
    </div>
    <div class="alert  alert-warning alert-dismissable" id="div_duplicados" style="display:none;"> 
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>        
         <h4><i class="fa fa-info" aria-hidden="true"></i> La persona seleccionada ya estaba inscripta en la instancia</h4>         
    </div>
    <div class="panel-group" id="accordion_persona">
    
    <div class="row">
            

                <?php
                    if ($model->isNewRecord)
                    { echo '
                        <div class="col-md-3">';
                                echo $form->field($model, 'dni_search')->textInput(["id" => "txtDNI_search",'maxlength' => true])->label("DNI") ;                                
                            echo '</div>';                                                   
                        echo '
                        <div class="col-md-3" style="padding-top:25px;">';
                            echo Html::button('<i class="glyphicon glyphicon-search"></i>', [                                    
                                    'class' => 'btn btn-primary btn-flat',                                    
                                    'name' => 'btn_dni',
                                    'id' => 'btn_dni',
                                    'title' => Yii::t('app', 'Buscar DNI'),
                                    
                                ]);
                                echo 
                                Html::button('Nueva Busqueda', [
                                    
                                    'class' => 'btn btn-primary btn-flat',
                                    'style' => 'display: none;',                                                                    
                                    'id' => 'btnnewsearch',                                     
                                    'title' => Yii::t('app', 'Crear Nueva Persona'),
                                ]);    
                                echo 
                            Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                                    'value' => Url::to(['mds_cap_persona/create']),
                                    'class' => 'btn btn-success btn-flat',                                                                    
                                    'id' => 'btnContacto', 
                                    'onclick' => '$("#abm_persona").show();$("#btnGuardar").hide();$("#btnCerrar").hide();',
                                    'title' => Yii::t('app', 'Crear Nueva Persona'),
                                ]);
                                
                                
                            echo '
                        
                        </div>   
                        <div class="col-md-6" style="padding-top:30px;text-align: left;" id="txt_mensaje">
                                
                        </div>
                        <div class="col-md-1" style="text-align: right;">';                        
                        echo $form->field($model, 'idpersonacap')->hiddenInput(['id' => 'idpersonacap'])->label(false) ;  
                        echo '                            
                        </div>                     
                        ';                    
                    }
                    else
                    {                                            
                        $cap = Mds_cap_persona::findOne($model->idpersonacap);
                        $per = Sds_com_persona::findOne($cap['idpersona']);                                                
                        $model->nombre_apell=$per['nombre'] . " " . $per['apellido'];
                        $model->el_dni=$per['documento'];
                        echo '<div class="col-md-6">';
                        echo  $form->field($model, 'nombre_apell')->textInput(['maxlength' => true,"readOnly"=>true])->label("Nombres y apellidos") ;                        
                        echo '</div>';
                        echo '<div class="col-md-6">';                        
                        echo  $form->field($model, 'el_dni')->textInput(['maxlength' => true,"readOnly"=>true])->label("DNI") ;
                        echo '</div>';
                    }
                ?>                                                    
        </div>
        <div class="row" style="display:<?=  $model->isNewRecord ? 'none' : 'block'?>;padding-top: 10px;" id="row_div1">

            <div class="col-md-12">
            <?php
                    if ($model->isNewRecord)
                    {
                       
                        echo 
                        $form->field($model, 'idcapinstancia')->widget(
                            Select2::classname(),
                            [
                                'data' => $filterInstancias,
                              //  "disabled" => !$model->isNewRecord,
                                'options' => [
                                    'id' => 'id_instancia',
                                    'placeholder' => 'Seleccionar instancia ...',
                                    'onchange' => 'changeInstancia()',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]
                        )->label('Instancia');
                        
                   
                    }
                    else
                    {

                        $cap_instancia = Mds_cap_instancia::findOne($model->idcapinstancia);                                                             
                        $model->dni_search=$cap_instancia->descripcion;
                        echo  $form->field($model, 'dni_search')->hiddenInput(['maxlength' => true,"readOnly"=>true])->label("Instancia") ;
                        
                    }
                        
             ?>
            </div>
        </div>
        <div class="row" style="display:<?=  $model->isNewRecord ? 'none' : 'block'?>;padding-top: 10px;" id="row_div2">
            <div class="col-md-6">

                <?= $form->field($model, 'termino')->dropDownList(
                    [
                        Mds_cap_inscripcion::ESTADO_INSCRIPTO => "Inscripto",
                        Mds_cap_inscripcion::ESTADO_ENCURSO => "En curso",
                        Mds_cap_inscripcion::ESTADO_APROBADO => "Aprobado",
                        Mds_cap_inscripcion::ESTADO_DESAPROBADO => "Desaprobado",
                        Mds_cap_inscripcion::ESTADO_ABANDONADO => "Abandonado",
                        Mds_cap_inscripcion::ESTADO_ENESPERA => "En espera",
                        Mds_cap_inscripcion::ESTADO_PARTICIPO => "Participa",
                        Mds_cap_inscripcion::ESTADO_NO_CORRESPONDE => "No Corresponde"
                
                    ],
                    ['id' => 'termino'],
                    ['prompt' => '-- Seleccione una opción --']
                ) ?>

            </div>
            <div class="col-md-6">
                <?php
                if ($model->fecha_inscripcion != null) {
                    $model->fecha_inscripcion = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_inscripcion)));
                }
                echo $form->field($model, 'fecha_inscripcion')->widget(DatePicker::ClassName(), [
                    'name' => 'check_issue_date_desde',
                    'language' => 'es',
                    'readonly' => false,
                    // 'layout' => '{picker}{input}{remove}',
                    'layout' => !$model->isNewRecord ? '{picker}{input}' : '{picker}{input}{remove}',

                    'options' => [
                        'id' => 'fecha_desde',
                        'class' => 'form-control input-md',
                        "disabled" => !$model->isNewRecord,
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ]
                ]);
                ?>
            </div>
        </div>
        <div id="div_adicional" class="row" style="display:none;padding-top: 10px;" >
            <div class="col-md-12">
                <label id="titulo"></label>
                <?= $form->field($model, 'dato_adicional')->textarea(['id'=>'dato_adicional','maxlength' => true, 'disabled' => $model->isNewRecord ? false : true])->label(false) ?>
            </div>
        </div>
    </div>
    <div class="row"  id="footer" >
        <div class="col-md-6">
    <?php  

        if ($model->isNewRecord)  
        { 
            echo                
            Html::button('Cerrar', [
                                        
            'class' => 'btn btn-default btn-flat float-right',                                                                                                      
            'id' => 'boton_cerrar',   
            'data-dismiss' => "modal"                                                                      
            ]);   
        } 
    
    ?>
    </div>
    <div class="col-md-6">
    <?php          
        if ($model->isNewRecord)
        {
            echo 
            Html::button('Guardar', [                
                'class' => 'btn btn-primary btn-flat',                                                                                                      
                'id' => 'boton_guardar', 
                'style' => 'display: none;',    
            ]); 
        }                                                            
    ?> 
    </div>
    <?php ActiveForm::end(); ?>

</div>

<div class="row" id="abm_persona" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Alumno
                </h3>
            </header>
            <div class="panel-body">
                <?php
                $model_persona = new Mds_cap_persona();
                echo $this->render('/mds_cap_persona/_form', [
                    'model' => $model_persona,
                    'botones' => true
                ]);
                ?>
            </div>
        </section>
    </div>
</div>


<?php
$this->registerJs(
    "$(document).ready(function() {        
        changeInstancia();
    });
    
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
                              
    
    "
);
?>

<script>

    function changeInstancia() {
        $.post("index.php?r=mds_cap_inscripcion/get_adicional&idinstancia=" + $("#id_instancia").val(), function(data) {
            if(data) {
                $("#mds_cap_inscripcion-titulo_dato_adicional").val(data);
                $('#titulo').html(data + ':');
                $("#div_adicional").show();
            } else {
                $("#mds_cap_inscripcion-dato_adicional").val(null);
                $('#titulo').html('');
                $("#div_adicional").hide();
            }
        });
    }
    function guardar_inscripcion() {      
        idpersonacap=$('#idpersonacap').val();
        id_instancia=$("#id_instancia").val();
        termino=$("#termino").val();
        fecha_desde=$("#fecha_desde").val();
        dato_adicional=$("#dato_adicional").val();
        dni_search=$("#dni_search").val();
        $.post("index.php?r=mds_cap_inscripcion/create2&idpersonacap="+idpersonacap+"&id_instancia="+id_instancia+"&termino="+termino+"&fecha_desde="+fecha_desde+"&dato_adicional="+dato_adicional+"&dni_search="+dni_search, function(data) {
                        
            if(data=="exito") {                                           
                $("#boton_guardar").hide();  
                $("#accordion_persona").hide();                  
                $("#save_ok").show();                                                  
            } else
            {
                if (data=="duplicado")  
                {
                    $("#div_duplicados").show();   
                     
                }
               
            }
        });
    }
    
    function reg_new_search() {
        
        $('#form_inscripcion')[0].reset();
        $("#row_div1").hide();
        $("#row_div2").hide();
        $("#div_adicional").hide();
        $("#btn_dni").show();        
        $("#txtDNI_search").removeAttr('readonly');
        $("#btnnewsearch").hide();        
        $('#txt_mensaje').html("");
        $("#boton_guardar").hide();
    }

    function datos_de_la_persona() {
        $("#div_duplicados").hide();   
        var dni_campo = $('#txtDNI_search').val();        
            if (dni_campo != '') {
                $('#txt_mensaje').html("Buscando datos de la Persona...");
                dni = dni_campo;                
                $.post("index.php?r=mds_cap_inscripcion/buscar_dni&dni=" + dni_campo, function(data) {                                       
                    data = $.parseJSON(data);
                    if (data.length == 0) {
                        $('#txt_mensaje').html("Registro no encontrado.<br>Pruebe con otro dni, o cree un nuevo registro");
                        $("#row_div1").hide();
                        $("#row_div2").hide();
                        $("#div_adicional").hide();
                        $("#boton_guardar").hide();                        
                    } else {

                        $('#txt_mensaje').html("Registro a nombre de <b>"+data[0]['nombre']+" "+data[0]['apellido']+"</b>, DNI <b>"+data[0]['documento']+"</b>");                        
                        $("#idpersonacap").val(data[1]['idpersonacap']);
                        $("#row_div1").show();
                        $("#row_div2").show();
                        $("#div_adicional").show();
                        $("#btnnewsearch").show();
                        $("#btn_dni").hide();
                        $("#txtDNI_search").attr("readonly","readonly");
                        $("#boton_guardar").show();                                               
                    }
                });
            }        
    }
 </script>
