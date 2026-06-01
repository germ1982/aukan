<?php

use app\controllers\SiteController;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\ConfiguracionTipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */
/* @var $form yii\widgets\ActiveForm */


?>


<div class="inventario-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-md-10">
            <?= SiteController::actionGet_input_select2($form, $model, 'idarticulo', 'cmb_articulo', Articulo::get_articulos(), 'idarticulo', 'descripcion', 'Articulo', 'seleccione articulo...') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cantidad')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php if ($model->origen_alta == 0): ?>
                <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'cmb_dispositivo', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Dispositivo', 'seleccione dispositivo...') ?>
            <?php else: ?>


                <label class="control-label"><?= $model->getAttributeLabel('iddispositivo') ?></label>
                <p class="form-control-static" style="background: #eee; padding: 6px 12px; border-radius: 4px;">
                    <?= $model->iddispositivo ? OrganismoDispositivo::findOne($model->iddispositivo)->descripcion : '' ?>
                </p>

                <?= $form->field($model, 'iddispositivo')->hiddenInput()->label(false) ?>

            <?php endif; ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-7">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado', 'cmb_empleado', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Empleado', 'seleccione empleado...') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idestado', 'cmb_estado', Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_ESTADO_ARTICULO), 'id_configuracion', 'descripcion', 'Estado', 'seleccione estado...') ?>
        </div>

        <div class=" col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>