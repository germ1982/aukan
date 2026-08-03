<?php

use app\controllers\SiteController;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Inventario $model */
/** @var app\models\Inventario $mothers */
/** @var app\models\Inventario $procesadores */
/** @var app\models\Inventario $fuentes */
/** @var app\models\Inventario $sistemas_operativos */
/** @var array $ram */
/** @var array $discos */
/** @var array $placas_video */
?>

<div class="row" id="div_caracteristicas_cpu" style="display: <?= $model->es_cpu ? 'block' : 'none' ?>; margin-top: 15px;">

    <!-- Columna 1: Componentes base -->
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <?= SiteController::actionGet_input_select2($form, $model, 'idmother', 'cmb_mother', $mothers, 'idarticulo', 'descripcion', 'Mother', 'seleccione Mother...') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= SiteController::actionGet_input_select2($form, $model, 'idmicro', 'cmb_micro', $procesadores, 'idarticulo', 'descripcion', 'Micro', 'seleccione Micro...') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= SiteController::actionGet_input_select2($form, $model, 'idso', 'cmb_so', $sistemas_operativos, 'id_configuracion', 'descripcion', 'Sistema Operativo', 'seleccione Sistema Operativo...') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= SiteController::actionGet_input_select2($form, $model, 'idfuente', 'cmb_fuente', $fuentes, 'idarticulo', 'descripcion', 'Fuente de Poder', 'seleccione Fuente de Poder...') ?>
            </div>
        </div>

        <!-- Placa de Video (Combo estático que postea al array de componentes) -->
        <div class="row">
            <div class="col-md-12">
                    <label class="control-label">Placa de Video</label>
                    <?= Html::dropDownList('Inventario[componentes_cpu][]', null, ArrayHelper::map($placas_video, 'idarticulo', 'descripcion'), [
                        'id' => 'cmb_placa_video',
                        'class' => 'form-control',
                        'prompt' => 'Sin Placa / Integrada'
                    ]) ?>

            </div>
        </div>
    </div>

    <!-- Columna 2: RAMs dinámicas -->
    <div class="col-md-4">
        <div class="row" style="align-items: flex-end;">
            <div class="col-md-10">
                <?= $form->field($model, 'total_ram_gb')->textInput(['type' => 'number', 'placeholder' => 'Ej: 16'])->label('Total RAM (GB)') ?>
            </div>
            <div class="col-md-2" style="margin-top: 25px;">
                <button type="button" class="btn btn-success btn-sm btn-agregar-ram" title="Agregar Memoria RAM">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
        </div>

        <!-- Contenedor donde se agregan los combos de RAM -->
        <div id="contenedor_rams">
            <!-- Fila inicial por defecto -->
            <div class="row fila-componente-ram">
                <div class="col-md-10">
                    <label class="control-label label-ram">Memoria 1</label>
                    <?= Html::dropDownList('Inventario[componentes_cpu][]', null, \yii\helpers\ArrayHelper::map($ram, 'idarticulo', 'descripcion'), [
                        'class' => 'form-control select-ram-dinamico',
                        'prompt' => 'Seleccione RAM...'
                    ]) ?>
                </div>
                <div class="col-md-2" style="margin-top: 25px;">
                    <button type="button" class="btn btn-danger btn-sm btn-quitar-componente" title="Quitar">
                        <i class="glyphicon glyphicon-minus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna 3: Discos dinámicos -->
    <div class="col-md-4">
        <div class="row" style="align-items: flex-end;">
            <div class="col-md-10">
                <?= $form->field($model, 'total_disco_gb')->textInput(['type' => 'number', 'placeholder' => 'Ej: 512'])->label('Total Disco (GB)') ?>
            </div>
            <div class="col-md-2" style="margin-top: 25px;">
                <button type="button" class="btn btn-success btn-sm btn-agregar-disco" title="Agregar Disco">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
        </div>

        <!-- Contenedor donde se agregan los combos de Discos -->
        <div id="contenedor_discos">
            <!-- Fila inicial por defecto -->
            <div class="row fila-componente-ram">
                <div class="col-md-10">
                    <label class="control-label label-disco">Disco 1</label>
                    <?= Html::dropDownList('Inventario[componentes_cpu][]', null, \yii\helpers\ArrayHelper::map($discos, 'idarticulo', 'descripcion'), [
                        'class' => 'form-control select-disco-dinamico',
                        'prompt' => 'Seleccione Disco...'
                    ]) ?>
                </div>
                <div class="col-md-2" style="margin-top: 25px;">
                    <button type="button" class="btn btn-danger btn-sm btn-quitar-componente" title="Quitar">
                        <i class="glyphicon glyphicon-minus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>