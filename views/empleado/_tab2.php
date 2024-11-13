<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;

?>
<div class="row">

    <div class="col-md-6">
        <?= SiteController::actionGet_input_select2($form, $model, 'contratacion', 'cmb_contratacion', Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_DE_CONTRATACION), 'id_configuracion', 'descripcion', 'Contratacion', 'Seleccione Contratacion...') ?>
    </div>

    <div class="col-md-4">
        <?= SiteController::actionGet_input_select2($form, $model, 'categoria', 'cmb_categorias', Configuracion::get_configuraciones(ConfiguracionTipo::CATEGORIA_LABORAL), 'id_configuracion', 'descripcion', 'Categoria', 'Seleccione Categoria...') ?>
    </div>

    <div class="col-md-2" style="padding-top:30px;">
        <?= $form->field($model, 'fichado')->checkbox(['checked' => true]) ?>
    </div>

</div>

<div class="row">

    <div class="col-md-3">
        <?= SiteController::actionGet_input_fecha($form, $model, 'ingreso_administrativo', 'fecha_ingreso_administrativo', 'Ingreso Administrativo') ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'antiguedad_legal')->textInput() ?>
    </div>

    <div class="col-md-3">
        <?= SiteController::actionGet_input_fecha($form, $model, 'ingreso_real', 'fecha_ingreso_real', 'Ingreso Real') ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'antiguedad_total')->textInput() ?>
    </div>

</div>

<div class="row">

    <div class="col-md-2">
        <?= SiteController::actionGet_input_select2($form, $model, 'afiliacion', 'cmb_afiliacion', Configuracion::get_configuraciones(ConfiguracionTipo::AFILIACION_GREMIAL), 'id_configuracion', 'descripcion', 'Afiliacion Gremial', 'Seleccione Afiliacion...') ?>
    </div>

</div>

