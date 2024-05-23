<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_programa_monto */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<?php if (!Yii::$app->request->isAjax) : ?>

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
<?php endif ?>

<div class="mds-certificacion-programa-monto-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <label>Dirección</label>
            <?=
            Select2::widget([
                'name' => 'iddireccion',
                'id' => 'iddireccion',
                'value' => (!$model->isNewRecord) ? $model->iddireccion : null,
                'data' =>  $listDirecciones,
                'options' => [
                    'onchange' => 'cargarProgramas();',
                    'placeholder' => 'Seleccione Dirección',
                    'disabled' => ($model->isNewRecord) ? false : true,
                ]
            ]);
            ?>
        </div>
        <div class="col-md-6 required">
            <label>Programa</label>
            <?=
            Select2::widget([
                'name' => 'idprograma',
                'id' => 'cmb_programa',
                'data' => $model->isNewRecord ? '' : $listProgramas,
                'value' => (!$model->isNewRecord) ? $model->idprograma : null,
                'options' => [
                    'placeholder' => 'Seleccione Programa',
                    'disabled' => true
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'monto')->textInput(['min' => 0, 'max' => 100000000000, 'type' => 'number', 'step' => 'any', 'placeholder' => '$']); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" required id="divDatepickerInicio" name="divDatepickerInicio">
            <?php
            if ($model->fecha_inicio != null) {
                $model->fecha_inicio = date(
                    'd/m/Y',
                    strtotime(
                        str_replace(
                            '/',
                            '-',
                            $model->fecha_inicio
                        )
                    )
                );
            }
            echo $form
                ->field($model, 'fecha_inicio')
                ->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'fecha_inicio',
                        'class' => 'form-control input-md',
                        'disabled' => false,
                        'autocomplete' => 'off'
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                        'autoclose' => true
                    ]
                ]);
            ?>
            <small id="smallIni"></small>
        </div>
        <div class="col-md-6" required id="divDatepickerFin" name="divDatepickerFin">
            <?php
            if ($model->fecha_fin != null) {
                $model->fecha_fin = date(
                    'd/m/Y',
                    strtotime(
                        str_replace(
                            '/',
                            '-',
                            $model->fecha_fin
                        )
                    )
                );
            }
            echo $form
                ->field($model, 'fecha_fin')
                ->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'fecha_fin',
                        'class' => 'form-control input-md',
                        'disabled' => false,
                        'autocomplete' => 'off'
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                        'autoclose' => true
                    ],
                ]);
            ?>
            <small id="smallFin"></small>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <br>
            <a class="btn btn-info" href="index.php?r=mds_certificacion_programa_monto/index">Volver</a>
            <?= Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJs(
    "$(document).ready(function() {
        $('#divDatepickerFin').on('changeDate', function(e) {
            validarFechas();
        });

        $('#divDatepickerInicio').on('changeDate', function(e) {
            validarFechas();
        });

    });"
); ?>


<script>
    function cargarProgramas() {
        const idProgramaSelected = $("#idProgramaSelected").val();
        let dato = '';
        $.post("index.php?r=mds_certificacion_programa/listado_programas&id=" + $("#iddireccion").val(), function(data) {
            if (data.length === 0) {
                $("#cmb_programa").html("<option value=null selected='true' disabled='disabled'>La dirección no cuenta con programas asignados</option>");
            } else {
                data = $.parseJSON(data);
                $.each(data, function(ind, elem) {
                    dato = dato + '<option value=' + elem.idprograma + '>' + elem.descripcion + '</option>';
                });
                let data1 = "<option value=null selected='true' disabled='disabled'>Seleccione Programa</option>" + dato;

                $("#cmb_programa").html(data1);
                $("#cmb_programa").prop("disabled", false);
                if (idProgramaSelected) {
                    $("#cmb_programa option[value='" + idProgramaSelected + "']").attr("selected", true);
                }
            }
        });
    }


    function validarFechas() {
        fechaDesde = $('#fecha_inicio').val();
        fechaFin = $('#fecha_fin').val();
        fechaInicioDias = fechaDesde.substr(0, 2);
        fechaInicioMes = fechaDesde.substr(3, 2);
        fechaInicioAño = fechaDesde.substr(6, 4);
        fechaFinDias = fechaFin.substr(0, 2);
        fechaFinMes = fechaFin.substr(3, 2);
        fechaFinAño = fechaFin.substr(6, 4);
        const fecha_desde = new Date(fechaInicioAño, fechaInicioMes, fechaInicioDias);
        const fecha_hasta = new Date(fechaFinAño, fechaFinMes, fechaFinDias);

        $('#btnGuardar').prop('disabled', false);
        $('#divDatepickerFin').removeClass('has-success has-error').addClass('has-success');
        $('#smallFin').text('');


        if (fechaDesde && fechaFin) {
            if (!(fecha_desde < fecha_hasta)) {
                $('#btnGuardar').prop('disabled', true);
                $('#divDatepickerFin').removeClass('has-success has-error').addClass('has-error');
                $('#divDatepickerFin').css('color', '#a94442');
                $('#smallFin').text('Fecha Fin debe ser mayor a Fecha Inicio.');
            }
        }

        $('#divDatepickerInicio').removeClass('has-success has-error').addClass('has-success');
        $('#smallIni').text('');

        if (!fechaDesde) {
            $('#btnGuardar').prop('disabled', true);
            $('#divDatepickerInicio').removeClass('has-success has-error').addClass('has-error');
            $('#divDatepickerInicio').css('color', '#a94442');
            $('#smallIni').text('Ingrese Fecha Inicio.');
        }
    }
</script>