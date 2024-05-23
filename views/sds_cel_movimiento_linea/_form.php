<?php

use app\models\Mds_org_organismo;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_cel_linea;
use app\models\Sds_cel_movimiento;
use app\models\Sds_cel_movimiento_linea;
use app\models\Sds_cel_plan;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_movimiento_linea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-cel-movimiento-linea-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($mensaje!=''):?>
        <div class="row">
            <div class="col-md-12 alert alert-<?=isset($mensaje['error'])?'danger':'success'?>">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?=isset($mensaje['error'])?'<b>'.$mensaje['error'].'</b>':$mensaje?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row" style="border-bottom:2px solid #efeefe; margin-bottom: 5px;">
        <!--<div class="col-md-6">
            <?= $form->field($model, 'usuario_carga')->textInput(['readonly'=>true])?>
            <?= $form->field($model, 'idusuario')->hiddenInput()->label(false)?>
        </div>-->
        <div class="col-md-4">
            <?= $form->field($model, 'solicitante')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    $contactos,
                    'idcontacto',
                    function($contactos){
                        return $contactos->legajo.' - '.$contactos->nombre;
                    }
                ),
                'options' => [
                    'placeholder' => '- Seleccionar Solicitante -'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                    ]
                ]);?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'tipo')->widget(Select2::class, [
                'data' =>ArrayHelper::map(
                    Sds_com_configuracion::find()->where(['and',
                        'idconfiguraciontipo='.Sds_com_configuracion_tipo::MOVIMIENTO_LINEA,
                        'descripcion<>"Alta"'
                    ])->orderBy(['descripcion'=>SORT_ASC])->all(),
                    'idconfiguracion',
                    function($configuracion){
                        if($configuracion->descripcion!='Alta')
                            return $configuracion->descripcion;
                    }
                ),
                'options' => [
                    'id' => 'tipo_movimiento',
                    'placeholder' => '- Seleccionar Tipo Movimiento -'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                    ]
                ])->label('Tipo Movimiento'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'idlinea')->widget(Select2::class, [
                'data' => '',
                'options' => [
                    'placeholder' => '- Seleccionar Linea -',
                    'id' => 'select_linea'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                    ]
                ])->label('Numero de Linea');?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <span class="text-warning" id="alert-movimiento" style="display: none;">
                *Esta operación cambiará el responable del equipo asociado a la linea
            </span>
        </div>
        <div class="col-md-6">
            <label for="responsable_actual_nombre" class="control-label">Responsable Actual</label>
            <input type="text" class="form-control" readonly="true" name="responsable_actual_nombre" id="responsable_actual_nombre">
            <?= $form->field($model, 'responsable_anterior')->hiddenInput()->label(false)?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'responsable_nuevo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    $contactos,
                    'idcontacto',
                    function($contactos){
                        return $contactos->nombre.' - '.$contactos->legajo;
                    }
                ),
                'options' => [
                    'placeholder' => 'Seleccionar Responsable Nuevo...'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => true
                    ]
            ]);?>

            <input type="hidden" class="form-control" readonly="true" name="responsable_nuevo_id" id="responsable_nuevo_id">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label for="equipo_actual_nombre" class="control-label">Equipo Actual</label>
            <input type="text" class="form-control" readonly="true" name="equipo_actual_nombre" id="equipo_actual_nombre">
            <?= $form->field($model, 'equipo_anterior')->hiddenInput()->label(false)?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'equipo_nuevo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_bdc_equipo::findBySql(
                        'SELECT e.* FROM sds_bdc_equipo e WHERE e.idequipo IN
                            (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                                (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                    (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                    JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                                    GROUP BY mov.idequipo
                                ) um ON m2.idmovimiento=um.ultimo_movimiento
                            WHERE m2.tipo='.Sds_bdc_movimiento::MOV_ALTA.
                            ' OR m2.tipo='.Sds_bdc_movimiento::MOV_CAM_RESPONSABLE.
                            ' OR m2.tipo='.Sds_bdc_movimiento::MOV_REPARACION.') 
                            AND e.tipo='.Sds_bdc_equipo::CELULAR.
                            ' AND e.idequipo NOT IN (SELECT l.idequipo FROM sds_cel_linea l WHERE NOT ISNULL(l.idequipo))'
                    )->all(),
                    'idequipo',
                    function($equipo){
                        return '#'.str_pad($equipo->idequipo,6,"0", STR_PAD_LEFT).' | '.$equipo->getMarca_modelo();
                    }
                ),
                'options' => ['placeholder' => 'Seleccionar Equipo...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => true
                ],
            ]); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <label for="plan_actual_nombre" class="control-label">Plan Actual</label>
            <input type="text" class="form-control" readonly="true" name="plan_actual_nombre" id="plan_actual_nombre">
            <?= $form->field($model, 'plan_anterior')->hiddenInput()->label(false)?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'plan_nuevo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_cel_plan::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idplan',
                    'descripcion'
                ),
                'options' => ['placeholder' => 'Seleccionar Plan ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => true
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label for="organismo_actual_nombre" class="control-label">Organismo Actual</label>
            <input type="text" class="form-control" readonly="true" name="organismo_actual_nombre" id="organismo_actual_nombre">
            <?= $form->field($model, 'organismo_anterior')->hiddenInput()->label(false)?>
        </div>
        <div class="col-md-6">
        <label for="organismo_nuevo_nombre" class="control-label">Organismo Nuevo</label>
            <input type="text" class="form-control" readonly="true" name="organismo_nuevo_nombre" id="organismo_nuevo_nombre">
            <?= $form->field($model, 'organismo_nuevo')->hiddenInput()->label(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label for="organismo_cuenta_actual" class="control-label">Organismo Cuenta Actual</label>
            <input type="text" class="form-control" readonly="true" name="organismo_cuenta_actual" id="organismo_cuenta_actual">
            <?= $form->field($model, 'organismo_cuenta_anterior')->hiddenInput()->label(false)?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'organismo_cuenta_nuevo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Mds_org_organismo::find()->where('nivel in (1,2)')->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idorganismo',
                    'descripcion'
                ),
                'options' => ['placeholder' => 'Seleccionar Organismo Cuenta ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => true
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 5]) ?>
        </div>
        <!-- <div class="col-md-6">
            <?= $form->field($model, 'adjunto')->textarea(['rows' => 5, 'readonly'=>true]) ?>
        </div> -->
    </div>  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
</div>

<?php
$baja=Sds_cel_movimiento_linea::MOV_BAJA;
$suspRobo=Sds_cel_movimiento_linea::MOV_SUSP_ROBO;
$cambEquipo=Sds_cel_movimiento_linea::MOV_CAMBIO_EQUIPO;
$cambChip=Sds_cel_movimiento_linea::MOV_CAMBIO_CHIP;
$cambResp=Sds_cel_movimiento_linea::MOV_CAMBIO_RESP;
$cambPlan=Sds_cel_movimiento_linea::MOV_CAMBIO_PLAN;
$suspDesc=Sds_cel_movimiento_linea::MOV_SUSP_DESCONOCIDO;
$linea=$model->idlinea;

$script = <<<  JS
$(document).ready(function() {
    if($('#tipo_movimiento').val()!=''){
        for_type($('#tipo_movimiento').val(), $linea);
    }
    if($('#select_linea').val()!=''){
        set_data_for_linea($('#select_linea').val());
    }

    $('#tipo_movimiento').change(function(){
        for_type($(this).val());
    });

    $("#select_linea").change(function(){
        if($(this).val()==null){
            reset_datos_actuales();
        }else{
            set_data_for_linea($(this).val());
        }
    });

    $("#sds_cel_movimiento_linea-equipo_nuevo").change(function(){
        $.post("index.php?r=sds_cel_movimiento_linea/get_data_equipo&id="+$(this).val(), function(data){
            if(data!=''){
                //Asigno valor y actualizo widget de responsable_nuevo
                settings = $("#sds_cel_movimiento_linea-responsable_nuevo").attr('data-krajee-select2');
                $("#sds_cel_movimiento_linea-responsable_nuevo").val(data['responsable'].id);
                settings = window[settings];
                $("#sds_cel_movimiento_linea-responsable_nuevo").select2(settings);
                $("#responsable_nuevo_id").val(data['responsable'].id);
                //Asigno organismo nuevo
                $("#organismo_nuevo_nombre").val(data['organismo'].descripcion);
                $("#sds_cel_movimiento_linea-organismo_nuevo").val(data['organismo'].id);

                console.log($("#sds_cel_movimiento_linea-responsable_nuevo").val(), 'FIN');
            }
        });
    });

    function for_type(type, linea_select=''){
        habilita_campos(type);
        var options='';
        if(type!=''){
            $.post("index.php?r=sds_cel_movimiento_linea/get_equipos&tipo="+type, function(data){
                if(data!=''){
                    //Seteo al Select de lineas las opciones disponibles
                    for(const property in data){//Recorro el objeto devuelto
                        var linea = data[property];
                        options+='<option value="'+linea.idlinea+'">'+linea.numero+'</option>';
                    }
                    reset_datos_actuales();
                    reset_select_linea(options, linea_select);
                }
            });
        }else{
            reset_select_linea(options);
            reset_datos_actuales();
        }
    }

    function set_data_for_linea(linea){
        $.post("index.php?r=sds_cel_movimiento_linea/get_data_linea&id="+linea, function(data){
            if(data!=''){
                if(data['responsable']!=null){
                    var responsable=data['responsable'];
                    $("#responsable_actual_nombre").val(responsable.nombre);
                    $("#sds_cel_movimiento_linea-responsable_anterior").val(responsable.id);
                }else{
                    $("#responsable_actual_nombre").val('- SIN DATOS -');
                    $("#sds_cel_movimiento_linea-responsable_anterior").val(null);
                }

                if(data['equipo']!=null){
                    var equipo=data['equipo'];
                    $("#equipo_actual_nombre").val(equipo.nombre);
                    $("#sds_cel_movimiento_linea-equipo_anterior").val(equipo.id);
                }else{
                    $("#equipo_actual_nombre").val('- SIN DATOS -');
                    $("#sds_cel_movimiento_linea-equipo_anterior").val(null);
                }

                if(data['plan']!=null){
                    var plan=data['plan'];
                    $("#plan_actual_nombre").val(plan.descripcion);
                $("#sds_cel_movimiento_linea-plan_anterior").val(plan.id);
                }else{
                    $("#plan_actual_nombre").val('- SIN DATOS -');
                    $("#sds_cel_movimiento_linea-plan_anterior").val(null);
                }

                if(data['organismo']!=null){
                    var organismo=data['organismo'];
                    $("#organismo_actual_nombre").val(organismo.nombre);
                    $("#sds_cel_movimiento_linea-organismo_anterior").val(organismo.id);
                }else{
                    $("#organismo_actual_nombre").val('- SIN DATOS -');
                    $("#sds_cel_movimiento_linea-organismo_anterior").val(null);
                }

                if(data['organismo_padre']!=null){
                    var organismo_padre=data['organismo_padre'];
                    $("#organismo_cuenta_actual").val(organismo_padre.nombre);
                    $("#sds_cel_movimiento_linea-organismo_cuenta_anterior").val(organismo_padre.id)
                }else{
                    $("#organismo_cuenta_actual").val('- SIN DATOS -');
                    $("#sds_cel_movimiento_linea-organismo_cuenta_anterior").val(null);
                }
            }
        });
    }

    function reset_datos_actuales(){
        $("#responsable_actual_nombre").val('');
        $("#sds_cel_movimiento_linea-responsable_anterior").val('');
        $("#equipo_actual_nombre").val('');
        $("#sds_cel_movimiento_linea-equipo_anterior").val('');
        $("#plan_actual_nombre").val('');
        $("#sds_cel_movimiento_linea-plan_anterior").val('');
        $("#organismo_actual_nombre").val('');
        $("#sds_cel_movimiento_linea-organismo_anterior").val('');
        $("#organismo_cuenta_actual").val('');
        $("#sds_cel_movimiento_linea-organismo_cuenta_anterior").val('');
    }

    function reset_select_linea(options, linea=''){
        $("#select_linea").html(options);
        //Obtengo y reinicio el select2 de equipos
        settings = $("#select_linea").attr('data-krajee-select2');
        $("#select_linea").val(linea);
        settings = window[settings];
        $("#select_linea").select2(settings);
    }

    function habilita_campos(tipo){
        $("#sds_cel_movimiento_linea-responsable_nuevo").attr('disabled', true);
        $("#sds_cel_movimiento_linea-equipo_nuevo").attr('disabled', true);
        $("#sds_cel_movimiento_linea-plan_nuevo").attr('disabled', true);
        $("#sds_cel_movimiento_linea-organismo_nuevo").attr('readonly', true);
        $("#sds_cel_movimiento_linea-adjunto").attr('readonly', true);
        $("#sds_cel_movimiento_linea-organismo_cuenta_nuevo").attr('disabled', true);
        $("#alert-movimiento").hide();

        if(tipo==$suspRobo){
            $("#sds_cel_movimiento_linea-adjunto").attr('readonly', false);
        }
        if(tipo==$cambEquipo){
            $("#sds_cel_movimiento_linea-equipo_nuevo").attr('disabled', false);
            $("#sds_cel_movimiento_linea-adjunto").attr('readonly', false);
            $("#sds_cel_movimiento_linea-organismo_cuenta_nuevo").attr('disabled', false);
        }
        if(tipo==$cambPlan){
            $("#sds_cel_movimiento_linea-plan_nuevo").attr('disabled', false);
        }
        if(tipo==$cambResp){
            $("#sds_cel_movimiento_linea-responsable_nuevo").attr('disabled', false);
            $("#sds_cel_movimiento_linea-organismo_cuenta_nuevo").attr('disabled', false);
            $("#alert-movimiento").show();
        }
    }
});
JS;
$this->registerJs($script);
?>
