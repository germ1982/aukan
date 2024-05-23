<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = $model->isNewRecord ? "Nuevo Registro" : "Actualizar Registro #{$model->idcertificacionprograma}";
/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_programa */
/* @var $form yii\widgets\ActiveForm */

?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<?php $form = ActiveForm::begin(); ?>
<div class="mds-certificacion-programa-form">
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

    <div class="row">
        <div class="col-md-6">
            <?= $form
                ->field($model, 'idprograma')
                ->widget(Select2::class, [
                    'data' => $listProgramas,
                    'options' => [
                        'placeholder' =>
                        'Seleccione...',
                        'tabIndex' => '1',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'disabled' => ($model->isNewRecord) ? false : true,
                ])
                ->label('<b>Programa</b>') ?>
        </div>
        <div class="col-md-6">
            <?= $form
                ->field($model, 'idcertificaciondireccion')
                ->dropDownList(
                    $listDirecciones,
                    [
                        'prompt' => [
                            'text' =>
                            'Seleccione...',
                            'options' => [
                                'disabled' => true,
                                'selected' => true,
                            ],
                        ],
                        'disabled' => ($model->isNewRecord) ? false : true,
                    ]
                )
                ->label('<b>Dirección/Área</b>') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form
                ->field($model, 'cambio_responsable')
                ->dropdownList(
                    [
                        1 => 'Si',
                        0 => 'No',
                    ],
                    [
                        'prompt' => [
                            'text' => 'Seleccione...',
                            'options' =>
                            [
                                'disabled' => true,
                                'selected' => true
                            ]
                        ]
                    ]
                )->label('<b>¿Permitir cambio de responsable?</b>') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model_certificacion_programa_monto, 'monto')->textInput(['min' => 0, 'max' => 100000000000, 'type' => 'number', 'step' => 'any', 'placeholder' => '$'])->label('<b>Monto</b>'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form
                ->field($model, 'requiere_autorizacion')
                ->dropdownList(
                    [
                        1 => 'Si',
                        0 => 'No',
                    ],
                    [
                        'prompt' => [
                            'text' => 'Seleccione...',
                            'options' =>
                            [
                                'disabled' => true,
                                'selected' => true
                            ]
                        ],
                        'onChange' => 'verificarAutorizacion()',
                    ]
                )->label('<b>¿Requiere autorización previa?</b>'); ?>
        </div>
        <div class="col-md-6 required" id="cantidadNiveles">
            <?= $form
                ->field($model, 'cant_niveles_autorizacion')
                ->dropdownList(
                    $cantidadNiveles,
                    [
                        'prompt' => [
                            'text' => 'Seleccione...',
                            'options' =>
                            [
                                'disabled' => true,
                                'selected' => true
                            ]
                        ]
                    ]
                )->label('<b>Cantidad de niveles de autorización</b>'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form
                ->field($model, 'idtipo_subsidio')
                ->widget(Select2::class, [
                    'data' => $listTipoSubsidio,
                    'options' => [
                        'placeholder' =>
                        'Seleccione...',
                        'tabIndex' => '1',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    //'disabled' => ($model->isNewRecord) ? false : true,
                ])
                ->label('<b>Tipo de subsidio</b>') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 required">
            <label><b>Documentación obligatoria</b></label>
            <?=
            Select2::widget([
                'name' => 'adjunto',
                'id' => 'adjunto',
                'value' => ($model->isNewRecord)  ? '' : $selectAdjuntos,
                'data' => $listTipoAdjuntos,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'multiple' => true,
                    'required' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'showToggleAll' => false,
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <label><b>Documentación sugerida</b></label>
            <?=
            Select2::widget([
                'name' => 'adjunto_sugerido',
                'id' => 'adjunto_sugerido',
                'value' => ($model->isNewRecord) ? '' : $selectAdjuntosSugeridos,
                'data' => $listTipoAdjuntos,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'multiple' => true,
                    'required' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'showToggleAll' => false,
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 required">
            <label><b>Requisitos</b></label>
            <?=
            Select2::widget([
                'name' => 'requisito',
                'id' => 'requisito',
                'value' => $model->isNewRecord ? '' : $selectRequisitos,
                'data' => $listRequisitos,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'multiple' => true,
                    'required' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'showToggleAll' => false,
            ]);
            ?>
        </div>
    </div>

    <br>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <a class="btn btn-info" href="index.php?r=mds_certificacion_programa/index">Volver</a>
            <?= Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
</div>

<?php
$this->registerJs(
    "$(document).ready(function() {
        verificarAutorizacion();
        precargarSelectAdjuntos();
    })"
);
?>

<script>
    function verificarAutorizacion() {
        if ($('#mds_certificacion_programa-requiere_autorizacion').val() == 1) {
            $('#cantidadNiveles').show();
        } else {
            $('#mds_certificacion_programa-cant_niveles_autorizacion').val('');
            $('#cantidadNiveles').hide();
        }
    }

    function precargarSelectAdjuntos() {
        const arraySugeridos = $("#adjunto_sugerido").val();
        modificarSelectAdjuntoObligatorio(arraySugeridos);
        const arrayObligatorios = $("#adjunto").val();
        modificarSelectAdjuntoSugerido(arrayObligatorios);
    }

    $("#adjunto").on("change", function() {
        const selectedValue = $(this).val();
        modificarSelectAdjuntoSugerido(selectedValue);
    });

    $("#adjunto_sugerido").on("change", function() {
        const selectedValue = $(this).val();
        modificarSelectAdjuntoObligatorio(selectedValue);
    });

    function modificarSelectAdjuntoObligatorio(array) {
        $("#adjunto option").each(function() {
            if (array.includes($(this).val())) {
                $(this).prop("disabled", true);
            } else {
                $(this).prop("disabled", false);
            }
        });
    }

    function modificarSelectAdjuntoSugerido(array) {
        $("#adjunto_sugerido option").each(function() {
            if (array.includes($(this).val())) {
                $(this).prop("disabled", true);
            } else {
                $(this).prop("disabled", false);
            }
        });
    }
</script>

<?php ActiveForm::end(); ?>