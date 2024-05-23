<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_solicitud_intermedia;
use app\models\Sds_ent_tipo;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

CrudAsset::register($this);

$this->title = 'Cargar Solicitud de Entrega Intermedia';

$idPermisos = Mds_seg_permiso::getPermisosByIdUsuario($model->usuario_carga)->all();
$alta_responsable = 0;
foreach ($idPermisos as $r) :
    switch ($r->iditem) {
        case Mds_seg_item::MODULO_ENT_CAMBIO_RESPONSABLE:
            $alta_responsable = $r->alta;
            break;
    }
endforeach;

function botonAltaReceptor()
{
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btnReceptor', 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        'onclick' => '
          $("#abm_configuracion").modal("show")
          .find("#content_abm")
          .load($(this).attr("value"));
          $("#header_abm").html("Nuevo Receptor");
          $("#btnGuardar").hide();$("#btnCerrar").hide();
          $("#main_form").hide();'
    ]);
}

?>
<style>
    .content-body{
        padding-top: 20px;
        padding-bottom: 0px;
    }
</style>
<?php
if(isset($post)){
    echo '<br><br><br><b>'.$post.'</b>';
}

$request = Yii::$app->request; //Obtengo el tipo de peticion para renderizar dependiendo de su valor
if(!$request->isAjax):?>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<?php endif ?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?php if($messageOk): ?>
                <div class="alert alert-success" style="margin-bottom: 20px; min-height: 65px;">
                    <div class="col-md-5">
                        <span style="margin-left: 20px;"><b>¡La solicitud fue cargada de manera correcta!</b></span>
                    </div>
                    <div class="col-md-3">
                        <?php $solicitud = Sds_ent_solicitud_intermedia::findOne($id_solicitud);
                        $entregado= $solicitud->estado==Sds_ent_solicitud_intermedia::ESTADO_ENTREGADA ? true:false?>
                        <?php if($permiso_entrega!=null && !$entregado):?>
                            <?=
                            Html::a('<span class= "fas fa-people-carry"></span> Realizar Entrega', $urlEntrega, [
                                'class' => 'btn btn-info pull-right',
                                'role' => 'modal-remote', 'title' => 'Generar Entrega',
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip'
                            ])
                            ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-primary pull-right" href="<?=Url::to(['//sds_ent_solicitud_intermedia/create'])?>">
                            <i class="glyphicon glyphicon-plus"></i>
                            Cargar nueva solicitud de Entrega
                        </a>
                    </div>
                </div>
                <?php else: ?>
                    <div class="row">
                        <?php if(!$request->isAjax) :?>
                            <div class="col-md-10 col-md-offset-1" style="border: 1px solid #ccc; border-top: none; border-radius: 3px; padding-bottom: 15px;">
                        <?php else: ?>
                            <div>
                        <?php endif ?>
                            <!-- break -->
                            <div id="main_form" class="sds-ent-solicitud-intermedia-form">

                                <?php $form = ActiveForm::begin(); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php
                                        $model->hora = date('H:i', strtotime(str_replace('/', '-', $model->fecha_hora)));
                                        $model->fecha_hora = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora)));

                                        echo $form->field($model, 'fecha_hora')->widget(DatePicker::class, [
                                            'name' => 'check_issue_date',
                                            'language' => 'es',
                                            'readonly' => false,
                                            'layout' => '{picker}{input}{remove}',
                                            'options' => [
                                                'id' => 'fecha_entrega',
                                                'tabIndex' => '1',
                                                'class' => 'form-control input-md',
                                                'disabled' => $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA
                                            ],
                                            'pluginOptions' => [
                                                'value' => null,
                                                'format' => 'dd/mm/yyyy',
                                                'endDate' => date('d/m/Y'),
                                                'todayHighlight' => true,
                                                'autoclose' => true,
                                            ]
                                        ])->label('Fecha (dd/mm/yyyy)'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?=
                                        $form->field($model, 'hora')->widget(TimePicker::class, [
                                            //'options' => ['value' =>'00:00'],
                                            'options' => [
                                                'id' => 'hora',
                                                'tabIndex' => '1',
                                                //'value' =>false,
                                                'class' => 'form-control input-sm',
                                                'disabled' => $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA
                                            ],
                                            'pluginOptions' => [
                                                'showSeconds' => false,
                                                'showMeridian' => false,
                                                'minuteStep' => 15,
                                                //'secondStep' => 5,
                                            ]
                                        ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'idtipo')->dropDownList(
                                            ArrayHelper::map(
                                                Sds_ent_tipo::find()->where("idtipo in (select idtipo from mds_seg_usuario_entrega_tipo ut where ut.idusuario="
                                                    . $model->usuario_carga . ")")->orderBy(['descripcion' => SORT_ASC])->all(),
                                                'idtipo',
                                                'descripcion'
                                            ),
                                            [
                                                'prompt' => 'Seleccionar Tipo Entrega ...',
                                                'id' => 'cmb_tipo',
                                                'tabIndex' => '1',
                                                'disabled' => $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA
                                            ]
                                        );
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <?= $form->field($model, 'emisor')->widget(Select2::class, [
                                                'data' => ArrayHelper::map(
                                                    [],
                                                    'identrega',
                                                    function ($model) {
                                                        return $model->toString();
                                                    }
                                                ),
                                                'options' => [
                                                    'placeholder' => 'Seleccionar Emisor ...',
                                                    'id' => 'cmb_emisor',
                                                    'tabIndex' => '1',
                                                    'disabled' => $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => false
                                                ],
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <?php Pjax::begin(['id' => 'pjax_config_' . Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA, 'timeout' => '5000']); ?>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <?= $form->field($model, 'receptor')->widget(Select2::class, [
                                                'data' => ArrayHelper::map(
                                                    Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA),
                                                    'idconfiguracion',
                                                    'descripcion'
                                                ),
                                                'options' => [
                                                    'placeholder' => 'Seleccionar Receptor ...',
                                                    'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA,
                                                    'tabIndex' => '1',
                                                    'disabled' => $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ]);
                                            ?>
                                            <?php if($request->isAjax):?>
                                                <span class="input-group-btn">
                                                    <?= $alta_responsable != 0 ? botonAltaReceptor() : "" ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php Pjax::end(); ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'saldo')->textInput(["type" => "number", "readOnly" => true, 'disabled' => $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA]) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'cantidad')->textInput(['tabIndex' => '1', "type" => "number", 'disabled' => $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'observaciones')->textarea(['rows' => 6, "disabled" => $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA]) ?>
                                    </div>
                                </div>
                                <div class="row" style="display:<?= $model->estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA ? "block" : "none" ?>">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'motivo_rechazo')->textarea(['rows' => 3]) ?>
                                    </div>
                                </div>
                                
                                <?php if (!$request->isAjax) { ?>
                                    <div class="form-group">
                                        <?= Html::submitButton($model->isNewRecord ? 'Crear solicitud' : 'Actualizar', 
                                            ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-primary').' col-md-6 col-md-offset-3', 'type' => "submit"]) ?>
                                    </div>
                                <?php } ?>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 id="header_abm" class="panel-title">
                </h3>
            </header>
            <div class="panel-body" id="content_abm">
            </div>
        </section>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "clientOptions" => [
        "backdrop" => "static",
        //"keyboard" => false
    ],
    "options" => [
        "tabindex" => false // important for Select2 to work properly
    ],
    "closeButton" => false,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php
$this->registerJs(
    "
    function getSaldo() {
        $.post(\"index.php?r=sds_ent_entrega/get_saldo&idtipo=\" + $(\"#cmb_tipo\").val() + \"&identrega=\" + $(\"#cmb_emisor\").val(), function(data) {            
            var identregaemisor = Number($(\"#cmb_emisor\").val());
            var identregaeditar = Number(" . ($model->emisor != null ? $model->emisor : 0) . ");
            if (identregaeditar == identregaemisor) {
                data = Number(data);
            }
            $(\"#sds_ent_solicitud_intermedia-saldo\").val(data);
        });
    }

    function cargarEmisores() {
        if ($(\"#cmb_tipo\").val() != '') {
            var fecha_hora_entrega = $('#fecha_entrega').val();
            if (fecha_hora_entrega != null) {
                fecha_hora_entrega = formatearFecha($('#fecha_entrega').val());
                fecha_hora_entrega = fecha_hora_entrega + \" \" + $('#hora').val();
            }
            $.post(\"index.php?r=sds_ent_entrega/cmb_emisor&idtipo=\" + $(\"#cmb_tipo\").val() + \"&fecha_entrega=\" + fecha_hora_entrega,
                function(data) {
                    $(\"select#cmb_emisor\").html(data);
                    getSaldo();
                }
            );
        }
    }

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
    
    $(document).ready(function() {                        
        " . ($model->isNewRecord ? "cargarEmisores();" : "getSaldo();") .
        "$('#loading').hide();
    });    

    $('#cmb_tipo').change(function(){        
        cargarEmisores();
    });
    $('#cmb_emisor').change(function(){        
        getSaldo();
    });
    $('#fecha_entrega').change(function(){        
        cargarEmisores();
    });    
    "
);
?>