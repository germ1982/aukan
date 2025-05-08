<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionSearch;
use app\models\ConfiguracionTipo;
use app\models\OrganismoDispositivo;
use app\models\Empleado;

/* $model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));
$model->hora = $model->isNewRecord ? date('H:i') : date('H:i', strtotime($model->hora)); */

/* @var $this yii\web\View */
/* @var $model app\models\RegistroRecepcion */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .file-drop-zone {
        min-height: 100px !important;
    }

    .file-preview-image {
        min-height: 100px !important;
        max-width: 100% !important;
        /* Ajusta la imagen al 100% del contenedor */
        max-height: 100% !important;
        /* Define la altura máxima de la vista previa */
        object-fit: cover !important;
        /* Cubre el contenedor sin distorsión */

    }

    .krajee-default {
        min-height: 100px !important;
        float: none !important;
    }

    .kv-file-content {
        min-height: 100px !important;
        width: 100% !important;
    }
</style>

<div class="registro-recepcion-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'fecha', 'Fecha') ?>
        </div>
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_hora($form, $model, 'hora', 'input_hora', 'Hora') ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'dni')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class=" col-md-4">
            <?= $form->field($model, 'motivo')->textarea() ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'acceso')->dropDownList(
                \app\models\EdificioAcceso::getListaAccesos(),
                ['prompt' => 'Seleccione tipo de acceso...']
            ) ?>

        </div>
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'id_dispositivo_derivacion', 'cmb_id_dispositivo_derivacion', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Dispositivo Derivacion', 'seleccione dispositivo...') ?>
        </div>
    </div>
    <div class=" row">
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'id_responsable_derivacion', 'cmb_id_responsable_derivacion', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Responsable Derivacion', 'seleccione empleado...') ?>
        </div>
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'id_tipo_recepcion', 'cmb_id_tipo_recepcion', Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_RECEPCION), 'id_configuracion', 'descripcion', 'Tipo Recepcion', 'seleccione tipo recepcion...') ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6, 'placeholder' => 'Ingrese una observación (opcional)']) ?>

        </div>


    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>



    <?php ActiveForm::end(); ?>

</div>