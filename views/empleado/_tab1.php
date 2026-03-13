<?php

use app\controllers\SiteController;
use app\helpers\AppBuscarPersonaHelper;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\file\FileInput;
use yii\helpers\Html;

if (isset($model->idpersona)) {
    $persona = Persona::findOne($model->idpersona);
    $model->documento = $persona->documento;
    //$persona_nombre = "$persona->apellido, $persona->nombre";
}

?>




<!-- 
<style>
    .linea_busqueda {
        margin-top: -20px;
    }
</style>
 -->


<?= Html::activeHiddenInput($model, 'documento', ['id' => 'input_documento']); ?>

<div class="row linea_busqueda">
    <!-- Linea de busqueda -->
    <div class="col-md-12">
        <?= AppBuscarPersonaHelper::widgetBuscarPersona($model, 'idpersona', 'Documento', 5, 7) ?>
    </div>

</div>

<br>

<div class="row">

    <div class="col-md-3">
        <?= $form->field($model, 'legajo')->textInput() ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'cuil')->textInput() ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'telefono')->textInput() ?>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'cmb_dispositivos', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Sector', 'Seleccione Sector...') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <?= SiteController::actionGet_input_select2($form, $model, 'funcion', 'cmb_funcion', Configuracion::get_configuraciones(ConfiguracionTipo::FUNCION_LABORAL), 'id_configuracion', 'descripcion', 'Funcion', 'Seleccione Funcion...') ?>
    </div>

    <div class="col-md-4" style="padding-top:30px;">
        <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
    </div>
</div>