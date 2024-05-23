<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_registro */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-reproam-mandato-form">
                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'idregistro')->widget(Select2::class, [
                                'data' => $registros,
                                'options' => [
                                    'placeholder' => 'Seleccione registro...',
                                    'tabIndex' => '1',
                                    'id' => 'registroID',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php if ($model->isNewRecord) $model->titular = 'Titular'; ?>
                            <?=
                            $form->field($model, 'titular')->dropdownList(
                                [
                                    1 => "Titular",
                                    0 => "Suplente",
                                ],
                                [
                                    'Titular' => 'titular',
                                    'id' => 'titular',
                                ],
                            )
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" id="divDatepickerInicio" name="divDatepickerInicio">
                            <?php
                            if ($model->fecha_desde != null) {
                                $model->fecha_desde = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_desde)));
                            }
                            echo $form->field($model, 'fecha_desde')->widget(DatePicker::class, [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'fechaInicio',
                                    'class' => 'form-control input-md',
                                    'disabled' => false,
                                    'autocomplete' => 'off'
                                ],
                                'pluginOptions' => [
                                    'value' => null,
                                    'format' => 'dd/mm/yyyy',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ]
                            ]);
                            ?>
                        </div>
                        <div class="col-md-6" id="divDatepickerFin" name="divDatepickerFin">
                            <?php
                            if ($model->fecha_hasta != null) {
                                $model->fecha_hasta = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hasta)));
                            }
                            echo $form->field($model, 'fecha_hasta')->label("Fecha Hasta")->widget(DatePicker::class, [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'fechaFin',
                                    'class' => 'form-control input-md',
                                    'disabled' => false,
                                    'autocomplete' => 'off'
                                ],
                                'pluginOptions' => [
                                    'value' => null,
                                    'format' => 'dd/mm/yyyy',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ]
                            ]);
                            ?>
                            <small id="smallFin"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (!$model->observaciones) {
                                $model->observaciones = 'Presidente ';
                            } ?>
                            <?= $form->field($model, 'observaciones')->textarea(array('rows' => 3, 'cols' => 5)); ?>
                        </div>
                    </div>
                    <?php if ($puedeEliminar) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?=
                                $form->field($model, 'deleted_at')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],

                                )
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row"><br />
                        <div class="col-md-12">
                            <a class="btn btn-info" href="index.php?r=mds_reproam_mandato/index">Volver </a>
                            <?= Html::submitButton("Guardar", ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<?php
$this->registerJs(
    "$(document).ready(function() {  
        // Deshabilitamos el comportamiento de la tecla enter para que no haga un submit del form
        $('#btnSave').click(function(e){
        //     e.preventDefault();
        })   


        function validarFechas() {
            fechaDesde = $('#fechaInicio').val()
            fechaFin = $('#fechaFin').val()
            fechaInicioDias = fechaDesde.substr(0,2);
            fechaInicioMes = fechaDesde.substr(3,2);
            fechaInicioAño = fechaDesde.substr(6,4);
            fechaFinDias = fechaFin.substr(0,2);
            fechaFinMes = fechaFin.substr(3,2);
            fechaFinAño = fechaFin.substr(6,4);
            const fecha_desde = new Date(fechaInicioAño,fechaInicioMes,fechaInicioDias);
            const fecha_hasta = new Date(fechaFinAño,fechaFinMes,fechaFinDias);
        
            if(fechaDesde && fechaFin){
                if(fecha_desde < fecha_hasta) {
                    $('#btnSave').prop('disabled', false);
                    $('#divDatepickerFin').css('color', '#777');
                    $('#fechaFin').css('border-color', '#ccc');
                    $('#smallFin').text('');
                } else {
                    $('#btnSave').prop('disabled', true);
                    $('#divDatepickerFin').css('color', 'red');
                    $('#fechaFin').css('border-color', 'red');
                    $('#smallFin').text('Fecha Hasta debe ser mayor a Fecha Desde.');
                }
            }
        }

        $('#divDatepickerFin').on('changeDate', function(e) {
            validarFechas();
        });

        $('#divDatepickerInicio').on('changeDate', function(e) {
            validarFechas();
        });

    });"
);
?>