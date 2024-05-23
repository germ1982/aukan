<?php

use app\models\Mds_org_contacto;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

CrudAsset::register($this);

$this->title = 'Cargar Registro';
?>
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
                <a href="index.php?r=mds_hor_registro">Registros Horarios</a>
            </li>
            <li><span style="color: #fff"><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body" style="padding: 20px 10px; min-height: 560px;">
                <div class="mds-hor-registro-form">
                    <?php if(count($guardados)>1 && empty($hasFichadas)): ?>
                        <div class="alert alert-success alert-dismissable" id="alert-save">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <b class="text-center col-md-12 text-md" style="bottom: 20px;"><?=$guardados[0]?></b><br>
                            <ul style="position:relative; bottom: 25px;">
                                <?php foreach($guardados as $i => $guardado):?>
                                    <?php if ($i > 0):?>
                                        <li><?=$guardado?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if(count($errores)>1):?>
                        <div class="alert alert-danger alert-dismissable" id="alert-error">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <b class="text-center col-md-12 text-md" style="bottom: 20px;"><?=$errores[0]?></b>
                            <ul style="position:relative; bottom: 25px;">
                                <?php foreach($errores as $i => $error):?>
                                    <?php if ($i >0):?>
                                        <li><?=$error?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php $form = ActiveForm::begin();?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                                //$model->hora = date('H:i', strtotime(str_replace('/', '-', $model->fecha)));
                                //$model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
                            ?>
                            <div class="rounded" style="border: 1px solid #aaa; padding: 5px; background-color:#FFFEF7; color: #444;">
                                <?php
                                echo $form->field($model, 'fecha')->widget(DatePicker::class, [
                                    'name' => 'check_issue_date',
                                    'type' => $model->isNewRecord ? DatePicker::TYPE_INLINE : DatePicker::TYPE_INPUT,
                                    'language' => 'es',
                                    'readonly' => false,
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => [
                                        'id' => 'fecha_registro',
                                        'class' => 'form-control',
                                        'disabled' => false,
                                        //'onchange' =>   'verificar_fecha_existente()',
                                    ],
                                    'pluginOptions' => [
                                        'value' => null,
                                        'defaultDate' => null,
                                        'format' => 'dd/mm/yyyy',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                        'multidate' => true
                                    ]
                                ])->label('Fecha'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'presente')->radioList([1 => 'Presente', 0 => 'Ausente'])->label(false) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                                        'data' => ArrayHelper::map(
                                            $empleados,
                                            'idcontacto',
                                            function ($model) {
                                                return $model->legajo . " - " . $model->apellido . ", " . $model->nombre;
                                            }
                                        ),
                                        'options' => [
                                            'id' => 'cmb_contacto',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false,
                                            'placeholder' => 'Seleccione Empleado...'
                                        ],
                                    ])->label("Empleado"); ?>
                                </div>
                            </div>
                            <div class="row" id="presente">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'ingreso')->widget(TimePicker::class, [
                                        'options' => [
                                            'class' => 'form-control input-sm',
                                            'value' => isset($model->ingreso)?$model->ingreso:'08:00',
                                            'id' => 'ingreso'
                                        ],
                                        'pluginOptions' => [
                                            'showSeconds' => false,
                                            'showMeridian' => false,
                                            'minuteStep' => 15,
                                        ]
                                    ]); ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'egreso')->widget(TimePicker::class, [
                                        'options' => [
                                            'class' => 'form-control input-sm',
                                            'value' => isset($model->egreso)?$model->egreso:'15:00',
                                            'id' => 'egreso'
                                        ],
                                        'pluginOptions' => [
                                            'showSeconds' => false,
                                            'showMeridian' => false,
                                            'minuteStep' => 15,
                                        ]
                                    ]); ?>
                                </div>
                            </div>
                            <div class="row" id="ausente">
                                <div class="col-md-12">
                                    <?= $form->field($model_franco, 'tipo')->widget(Select2::class, [
                                            'data' => ArrayHelper::map(
                                                $tipos_franco,
                                                'idconfiguracion',
                                                'descripcion'
                                            ),
                                            'pluginOptions' => [
                                                'allowClear' => false,
                                                'placeholder' => 'Seleccione Tipo de Franco...'
                                            ],
                                        ]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'observaciones')->textarea(['rows' => 3, 'id' => 'observaciones']) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'reset_form')->checkbox() ?>
                                    <input type="hidden" name="confirm" value="0" id="confirmInput">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= Html::button(
                                            'Guardar',
                                            [
                                                'class' => 'btn btn-success col-md-12',
                                                'id' => 'save'
                                            ]
                                        ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
if(!empty($hasFichadas)):?>
    <div id="confirm" style="display: none; width: 350px;">
        <h3 class="text-warning text-center col-md-12">¿Continuar?</h3>
        <ul>
        <?php foreach($hasFichadas as $fichada):?>
            <li><?=$fichada?></li>
        <?php endforeach;?>
        </ul>
    </div>
<?php 
endif;
$script = <<<  JS
    $(document).ready(function(){
        if($('#confirm').css('display')!=undefined){
            $.confirm({
                title:'',
                content: $('#confirm').html(),
                buttons: {
                    cancel: {
                        text: 'Cancelar',
                        keys: ['n'],
                        action: function(){}
                    },
                    confirm: {
                        text: 'Continuar',
                        btnClass: 'btn-success',
                        keys: ['enter','q','c'],
                        action: function(){
                            if($("input:radio[name='Mds_hor_registro[presente]']:checked").val()!=0){
                                $('#mds_hor_franco-tipo').attr('disabled', true);
                            }
                            $('#confirmInput').val(1);
                            $('#w0').submit();
                        }
                    }
                }
            });
        }

        if($("input:radio[name='Mds_hor_registro[presente]']:checked").val()!=0){
            $('#presente').show();
            $('#ausente').hide();
        }else{
            $('#ausente').show();
            $('#presente').hide();
        }

        $("input:radio[name='Mds_hor_registro[presente]']").change(function(){
            if($("input:radio[name='Mds_hor_registro[presente]']:checked").val()!=0){
                $('#presente').show();
                $('#ausente').hide();
            }else{
                $('#presente').hide();
                $('#ausente').show();
            }
        });


        $('#save').click(function(){
            if($("input:radio[name='Mds_hor_registro[presente]']:checked").val()!=0){
                $('#mds_hor_franco-tipo').attr('disabled', true);
            }
            $('#w0').submit();
        });

        $('#save-next').click(function(){
            if($("input:radio[name='Mds_hor_registro[presente]']:checked").val()!=0){
                $('#mds_hor_franco-tipo').attr('disabled', true);
            }
            $('#next_day').val(1);
            $('#w0').submit();
        });
    });

    function verificar_fecha_existente(){
        $("#txr_msj").html("");
        $("#btnGuardarRegistroHorario").show();
        var id_contacto = $('#cmb_contacto').val();
        var fecha = $('#fecha_registro').val();
        $.post("index.php?r=mds_hor_registro/verificar_fecha_existente&id_contacto=" + id_contacto + "&fecha=" + fecha, function(data) {
            if(data.length > 0){
                $("#btnGuardarRegistroHorario").hide();
                $("#txr_msj").html("<p style='color: red;'>El empleado registra licencia "+data+"</p>");
            }
        });
    }
JS;
$this->registerJs($script);
?>