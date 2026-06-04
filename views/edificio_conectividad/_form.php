<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Edificio;

/* @var $this yii\web\View */
/* @var $model app\models\EdificioConectividad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="edificio-conectividad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idedificio')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-10">
            <div class="form-group">
                <label class="control-label">Edificio / Dependencia</label>
                <?= Html::textInput('edificio_nombre', Edificio::get_edificio_descripcion($model->idedificio), [
                    'class' => 'form-control',
                    'readonly' => true,
                    'style' => 'font-weight: bold; margin-bottom: 25px;'
                ]) ?>
            </div>
        </div>

        <div class="col-md-2">
            <?= SiteController::actionGet_input_select(
                $form,
                $model,
                'estado',
                'select-estado',
                Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_CONECTIVIDAD_ESTADO),
                'id_configuracion',
                'descripcion',
                'Estado del Enlace',
                'Seleccione...'
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select(
                $form,
                $model,
                'infraestructura',
                'select-infraestructura',
                Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_CONECTIVIDAD_SERVICIO),
                'id_configuracion',
                'descripcion',
                'Infraestructura',
                'Seleccione...'
            ) ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select(
                $form,
                $model,
                'servicio',
                'select-servicio',
                Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_CONECTIVIDAD_SERVICIO),
                'id_configuracion',
                'descripcion',
                'Servicio',
                'Seleccione...'
            ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'velocidad_en_mb', [
                'template' => '{label}<div class="input-group">{input}<span class="input-group-addon">Mbps</span></div>{error}'
            ])->textInput(['type' => 'number', 'min' => 0, 'placeholder' => 'Ej: 100']) ?>
        </div>

        <div class="col-md-3">
            <?= SiteController::actionGet_input_select(
                $form,
                $model,
                'tipo_conexion',
                'select-tipo-conexion',
                Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_CONECTIVIDAD_TIPO_CONEXION),
                'id_configuracion',
                'descripcion',
                'Tipo de Conexión',
                'Seleccione...'
            ) ?>
        </div>
    </div>

    <div class="row">


        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textarea([['rows' => 4], 'placeholder' => 'Notas internas...']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>