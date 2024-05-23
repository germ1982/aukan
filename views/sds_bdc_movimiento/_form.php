<?php

use app\models\Mds_org_contacto;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_movimiento */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Registrar Movimiento de Equipos';
$this->params['breadcrumbs'][] = $this->title;

//Almaceno los contactos en una variable para evitar realizar una consulta cada vez que se utiliza:
$all_contactos=Mds_org_contacto::findBySql("select * from mds_org_contacto c 
    join sds_com_persona p on p.idpersona=c.idpersona order by trim(p.nombre), p.apellido")->all();
$all_contactos_personas=ArrayHelper::map(
    $all_contactos,
    'idcontacto',
    function ($model) {
        return $model->nombre.' '.$model->apellido.' - '.$model->legajo;
});
?>
<style>
    .content-body{
        padding-top: 15px;
    }
    .select2-search{
        z-index: 1;
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li>
                <a href="index.php?r=sds_bdc_equipo">Equipos</a>
            </li>
            <li>
                <a href="index.php?r=sds_bdc_movimiento">Movimientos de Equipos</a>
            </li>
            <li><span><u><?= $this->title ?></u></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<?php //Alerts Success y Error:
if(Yii::$app->session->hasFlash('save_movimiento_equipo')) : ?>
    <div class="alert alert-success alert-dismissable" id="alert-save">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <b><?= Yii::$app->session->getFlash('save_movimiento_equipo') ?></b>
    </div>
<?php endif;

if (Yii::$app->session->hasFlash('fail_save_movimiento_equipo')) : ?>
    <div class="alert alert-danger alert-dismissable" id="alert-fail-save">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fas fa-times"></i> ¡Por favor intente nuevamente!</h4>
        <b><?= Yii::$app->session->getFlash('fail_save_movimiento_equipo') ?></b>
    </div>
<?php endif;?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body" style="padding-top: 15px;">
                <div class="sds-bdc-movimiento-form">
                    <?php $form = ActiveForm::begin();?>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <?= $form->field($model, 'fecha_hora')->widget(DatePicker::class, [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => true,
                                'layout' => '{picker}{input}',
                                'options' => [
                                    'id' => 'fecha_hora',
                                    'class' => 'form-control input-md'
                                ]
                            ]);?>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <?= $form->field($model, 'usuario_carga')->textInput(['readonly'=>true])?>
                            <?= $form->field($model, 'idusuario')->hiddenInput()->label(false)?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <?= $form->field($model, 'solicitante')->widget(Select2::class, [
                                'data' => $all_contactos_personas,
                                'options' => [
                                    'id' => 'responsable',
                                    'placeholder' => '- Seleccionar Solicitante del Movimiento -'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false,
                                ],
                            ]);
                            ?>
                        </div>

                        <div class="col-md-4 col-md-offset-2">
                            <?= $form->field($model, 'tipo')->widget(Select2::class, [
                                'data' => ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::BDC_MOVIMIENTO_TIPO),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                'options' => [
                                    'id' => 'tipo-movimiento',
                                    'placeholder' => '- Seleccionar Tipo de Movimiento -'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                    ]
                                ]);
                            ?>
                        </div>
                    </div>
                    <div class="row" id="check_preventivo" style="display:none;">
                        <div class="col-md-4 col-md-offset-7">
                            <?= $form->field($model, 'preventivo')->checkbox();?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <hr class="col-md-9 col-md-offset-2" style="border-color: #dcf; background-color: #dcf; margin: 15px 0 15px 135px;" />
                    </div>
                    <div class="col-md-10" style="background-color: #faf8f8; margin: 0 100px; padding: 10px 0 5px 80px; border-radius: 5px;">
                        <div class="row">
                            <div class="col-md-11">
                                <?= $form->field($model, 'equipos')->widget(Select2::class, [
                                    'data' => '',
                                    'options' => [
                                        'id' => 'all-equipos',
                                        'placeholder' => '- Seleccione Equipo(s) -'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false,
                                        'multiple' => true
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                        
                        <div id="form_resp_user" class="row" style="display: none;">
                            <?php include('_form_resp_user.php'); ?>
                        </div>
                        
                        <div id="form_change_ip" class="row" style="display: none;">
                            <?php include('_form_change_ip.php'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <hr class="col-md-8 col-md-offset-3" style="border-color: #ddccff87; background-color: #ddccff87; margin: 10px 0 20px 200px;" />
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <?php if (!Yii::$app->request->isAjax){ ?>
                	  	        <div class="form-group">
                	                <?= Html::submitButton('Guardar', ['class' => 'col-md-12 btn btn-success']) ?>
                	            </div>
                	        <?php } ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$baja=Sds_bdc_movimiento::MOV_BAJA;
$alta=Sds_bdc_movimiento::MOV_ALTA;
$cambio_responsable=Sds_bdc_movimiento::MOV_CAM_RESPONSABLE;
$reparacion=Sds_bdc_movimiento::MOV_REPARACION;
$home_office=Sds_bdc_movimiento::MOV_HOME_OFFICE;
$entrega_rep=Sds_bdc_movimiento::MOV_ENT_REPARACION;
$cambio_ip=Sds_bdc_movimiento::MOV_CAM_IP;
$equipo_select=(!empty($model->equipos)?$model->equipos[0]:0);

$script = <<<  JS
$(document).ready(function(){
    //Para visualizar bien el Select2 de equipos
    $('.select2-search__field').css('width', '100%');
    //Oculto la opcion de seleccionar todos los equipos en select multiple
    $('#s2-togall-all-equipos').hide();
    
    if($('#tipo-movimiento').val()!=''){
        getData($('#tipo-movimiento').val());
    }
    $('#tipo-movimiento').change(function(){
        getData($(this).val());
    });

    function getData(selectVal){
        $("#check_preventivo").css("display", "none");
        //Dependiendo del Tipo seleccionado, muestro el formulario correspondiente
        if(selectVal!=$cambio_ip){
            $('#form_resp_user').show();
            $('#form_change_ip').hide();
            $('#all-equipos').attr('multiple', true);
        }else{
            $('#form_change_ip').show();
            $('#form_resp_user').hide();
            $('#all-equipos').attr('multiple', false);
        }
        if(selectVal==$entrega_rep){
            $("#check_preventivo").css("display", "block");
        }

        //Consulto el servicio que devuelve los equipos segun su estado actual:
        $.post("index.php?r=sds_bdc_movimiento/filter_equipos&tipo="+selectVal, function(data){
            data = $.parseJSON(data);
            if(data!=''){
                //Seteo al Select equipos las nuevas opciones
                var options='';
                for(const property in data){//Recorro el objeto devuelto
                    if($equipo_select==property && selectVal==$cambio_ip){
                        options+='<option selected value="'+property+'">'+data[property]+'</option>';
                        getIp($equipo_select);
                    }
                    if($('#all-equipos').val()==property){//Para mantener seleccionado el primer id
                        options+='<option selected value="'+property+'">'+data[property]+'</option>';
                    }else{
                        options+='<option value="'+property+'">'+data[property]+'</option>';
                    }
                }
            }else{
               var options='';
            }
            $('#all-equipos').html(options);
        });

        //Obtengo y reinicio el select2 de equipos
        settings = $("#all-equipos").attr('data-krajee-select2');
        settings = window[settings];
        $("#all-equipos").select2(settings);
    }
});
JS;
$this->registerJs($script);
?>