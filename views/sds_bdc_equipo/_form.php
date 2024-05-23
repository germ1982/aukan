<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_bdc_equipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_equipo */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Cargar Equipo';

CrudAsset::register($this);
?>
<style>
    .content-body{
        padding-top: 20px;
    }
</style>
<?php if($model->isNewRecord):?>
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
                    <a href="index.php?r=sds_bdc_equipo">
                        <span>Equipos</span>
                    </a>
                </li>
                <li><span><u><?= $this->title ?></u></span></li>
            </ol>

            <div class="sidebar-right-toggle"></div>
        </div>
    </header>
<?php
endif;
if(isset($conflictIp)):?>
    <div id="confirm" style="display: none;"><?=$conflictIp?></div>
<?php endif;
//Almaceno los contactos en una variable para evitar realizar una consulta cada vez que se utiliza:
$all_contactos=Mds_org_contacto::findBySql("select * from mds_org_contacto c 
    join sds_com_persona p on p.idpersona=c.idpersona order by trim(p.nombre), p.apellido")->all();
$all_contactos_personas=ArrayHelper::map(
    $all_contactos,
    'idcontacto',
    function ($model) {
        return $model->nombre.' '.$model->apellido.' - '.$model->legajo;
});

//Alerts Success y Error:
if(Yii::$app->session->hasFlash('save_equipo')) : ?>
    <div class="alert alert-success alert-dismissable" id="alert-save">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <b><?= Yii::$app->session->getFlash('save_equipo') ?></b>
    </div>
<?php endif;

if (Yii::$app->session->hasFlash('fail_save_equipo')) : ?>
    <div class="alert alert-danger alert-dismissable" id="alert-fail-save">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fas fa-times"></i> ¡Por favor intente nuevamente!</h4>
        <b><?= Yii::$app->session->getFlash('fail_save_equipo') ?></b>
    </div>
<?php endif;?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body" style="padding-bottom: 30px;">
                <div class="sds-bdc-equipo-form">
                    <?php $form = ActiveForm::begin(['id' => 'form-au-equipo']); ?>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($model, 'tipo')->widget(Select2::class, [
                                        'data' => ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::BDC_TIPO_EQUIPO),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        'options' => [
                                            'placeholder' => '- Seleccionar Tipo de Equipo -'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group" id="group-marca">
                                        <?= $form->field($model, 'marca')->widget(Select2::class, [
                                            'data' => ArrayHelper::map(
                                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::BDC_MARCA_EQUIPO),
                                                'idconfiguracion',
                                                'descripcion'
                                            ),
                                            'options' => [
                                                'id'=>'config_'.Sds_com_configuracion_tipo::BDC_MARCA_EQUIPO,
                                                'placeholder' => '- Seleccionar Marca -'
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => false
                                                ]
                                            ]);
                                        ?>
                                        <span class="input-group-btn" style="padding-top: 27px;" id="parent-new-marca">
                                            <?= 
                                            Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                                                'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => Sds_com_configuracion_tipo::BDC_MARCA_EQUIPO]),
                                                'class' => 'btn btn-success btn-flat',
                                                'id' => 'btn_new_marca',
                                                'tabIndex' => '-1',
                                                'onclick' => '
                                                  $("#modal_abm").modal("show")
                                                  .find("#content_abm")
                                                  .load($(this).attr("value"));
                                                  $("#header_abm").html("Cargar Marca");'
                                            ]);
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6" style="padding-left: 0;">
                                        <?= $form->field($model, 'modelo')->textInput(['maxlength' => true]) ?>
                                    </div>
                                    <div class="col-md-6" style="padding-right: 0;">
                                        <?= $form->field($model, 'matricula')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                            </div>
                                    
                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($model, 'responsable')->widget(Select2::class, [
                                        'data' => $all_contactos_personas,
                                        'options' => [
                                            'id' => 'responsable',
                                            'placeholder' => '- Seleccionar Responsable -'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false,
                                            'disabled' => ($model->isNewRecord?false:true),
                                        ],
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'usuario')->widget(Select2::class, [
                                        'data' => $all_contactos_personas,
                                        'options' => ['placeholder' => '- Seleccionar Usuario -'],
                                        'pluginOptions' => [
                                            'allowClear' => false,
                                            'disabled' => ($model->isNewRecord?false:true),
                                        ],
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'idorganismo')->hiddenInput(['id'=>'idorganismo'])?>
                                    <?php
                                    if(!$model->isNewRecord){
                                        $sector=Mds_org_organismo::findOne($model->idorganismo);
                                    }
                                    ?>
                                    <?= $form->field($model, 'idorganismo')->textInput(['id'=>'sector', 'disabled'=>true, 'value'=>(isset($sector)?$sector->abreviatura:'')])?>
                                </div>
                            </div>
                                    
                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($model, 'procesador')->widget(Select2::class, [
                                        'data' => ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_PROCESADOR),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        'options' => [
                                            'placeholder' => '- Seleccionar Procesador -'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'disabled' => true
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'memoria')->widget(Select2::class, [
                                        'data' => ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_MEMORIA),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        'options' => [
                                            'placeholder' => '- Seleccionar Memoria -'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'disabled' => true
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'disco')->widget(Select2::class, [
                                        'data' => ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_DISCO),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        'options' => [
                                            'placeholder' => '- Seleccionar Disco -'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'disabled' => true
                                            ]
                                        ]);
                                    ?>                
                                </div>
                            </div>
                                    
                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($model, 'sistema_operativo')->widget(Select2::class, [
                                        'data' => ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_SISTEMA_OPERATIVO),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        'options' => [
                                            'placeholder' => '- Seleccionar Sistema Operativo -'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'disabled' => true
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'conectividad')->widget(Select2::class, [
                                        'data' => ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_CONECTIVIDAD),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        'options' => [
                                            'placeholder' => '- Seleccionar Conectividad -'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'disabled' => true
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($model, 'ip')->textInput(['maxlength' => true, 'pattern'=>'^((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])$', 'placeholder'=>'111.111.111.111', 'disabled'=>true]) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($model, 'imei')->textInput(['maxlength' => true, 'placeholder'=>'IMEI', 'disabled'=>true, 'type'=>'number']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (!Yii::$app->request->isAjax){ ?>
                	      	    <div class="form-group" style="padding-top: 15px;">
                                    <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'col-md-6 col-md-offset-3 '.($model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'), 'id'=>'btn-submit']) ?>
                	            </div>
                	        <?php } ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$script = <<<  JS
$(document).ready(function() {
    if($('#sds_bdc_equipo-tipo').val()!=''){
        enabledDisabled($('#sds_bdc_equipo-tipo').val());
    }
    $('#sds_bdc_equipo-tipo').change(function(){
        enabledDisabled($(this).val());
    });

    function enabledDisabled(val){
        $('#sds_bdc_equipo-procesador').attr('disabled', true);
        $('#sds_bdc_equipo-memoria').attr('disabled', true);
        $('#sds_bdc_equipo-disco').attr('disabled', true);
        $('#sds_bdc_equipo-sistema_operativo').attr('disabled', true);
        $('#sds_bdc_equipo-conectividad').attr('disabled', true);
        $('#sds_bdc_equipo-ip').attr('disabled', true);
        $('#sds_bdc_equipo-imei').attr('disabled', true);
        $.post("index.php?r=sds_bdc_equipo/get_data_tipo&pk="+val, function(data){
            data = $.parseJSON(data);
            if(data.procesador!=0){
                $('#sds_bdc_equipo-procesador').attr('disabled', false);
            }
            if(data.memoria!=0){
                $('#sds_bdc_equipo-memoria').attr('disabled', false);
            }
            if(data.disco!=0){
                $('#sds_bdc_equipo-disco').attr('disabled', false);                
            }
            if(data.sistema_operativo!=0){
                $('#sds_bdc_equipo-sistema_operativo').attr('disabled', false);
            }
            if(data.conectividad!=0){
                $('#sds_bdc_equipo-conectividad').attr('disabled', false);
            }
            if(data.ip!=0){
                $('#sds_bdc_equipo-ip').attr('disabled', false);
            }
            if(data.imei!=0){
                $('#sds_bdc_equipo-imei').attr('disabled', false);
            }
        });
    }


    $('#btn-submit').click(function(){
        var getCont = $('#group-marca').children('div').eq(0);
        var getError = getCont.hasClass('has-error');
        if(!getError){
            $('#parent-new-marca').css('padding-top', '0');
            $('#btn_new_marca').css('margin-top','-5px');
        }
    });

    $('#config_100').change(function(){
        var getCont = $('#group-marca').children('div').eq(0);
        var getError = getCont.hasClass('has-error');
        if(getError){
            $('#parent-new-marca').css('padding-top', '27px');
            $('#btn_new_marca').css('margin-top','0');
        }
    });


    $('.field-idorganismo').css('display', 'none');
    get_sector_responsable($('#responsable').val());
    $('#responsable').change(function(){
        get_sector_responsable($(this).val());
    });
    
    if($('#confirm').css('display')!=undefined){
        $.confirm({
            title:'',
            content: '<div style="text-align:center;">'+
                '<h4><span class="text-info">La IP </span><b>'+$('#sds_bdc_equipo-ip').val()+'</b></h4>'+
                '<h4><span class="text-info">se encuentra asignada a otro equipo</span></h4>'+
                '</div><div style="text-align:center; margin-top: 20px;"><h5><span class="text-warning">¿Desea quitarla del equipo actual?</span></h5></div>',
            buttons: {
                cancel: {
                    text: 'Cancelar',
                    keys: ['n'],
                    action: function(){}
                },
                somethingElse: {
                    text: 'Ver Actual',
                    btnClass: 'btn-info',
                    action: function(){
                        window.open('index.php?r=sds_bdc_equipo/view&id='+$('#confirm').html(), '_blank');
                    }
                },
                confirm: {
                    text: 'Quitar',
                    btnClass: 'btn-warning',
                    keys: ['enter','q','c'],
                    action: function(){
                        window.open('index.php?r=sds_bdc_movimiento/create&equipo='+$('#confirm').html()+'&tipo=2440', '_blank');
                    }
                }
            }
        });
    }

    function get_sector_responsable(responsable){
        $.post("index.php?r=sds_bdc_equipo/get_organismo_contacto&pk="+responsable, function(data){
            data = $.parseJSON(data);
            $('#sector').val(data.abreviatura);
            $('#idorganismo').val(data.idorganismo);
        });
    }
});
JS;
$this->registerJs($script);
?>
<?php Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    "id" => "modal_abm",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer" => "", // always need it for jquery plugin
]);
echo "<div id='content_abm'></div>";
Modal::end(); ?>