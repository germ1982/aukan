<?php

use app\controllers\SiteController;
use app\models\EdificioOficina;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrganismoDispositivo;
use app\models\Organismo;

$organismo = Organismo::findOne($model->idorganismo);

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDispositivo */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs("aplicarCorrector('input_descripcion');");
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

<div class="organismo-dispositivo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class=" col-md-6">
            <?= $form->field($model, 'descripcion')->textInput(['id' => 'input_descripcion']) ?>
        </div>
        <div class=" col-md-6">
            <?= SiteController::actionGet_input_select2($form, $model, 'idoficina', 'cmb_oficina', EdificioOficina::find()->orderBy('descripcion')->all(), 'idoficina', 'descripcion', 'Oficina') ?>
        </div>
    </div>

    <div class="row">
        <div class=" col-md-6">
            <?= SiteController::actionGet_input_select2($form, $model, 'idorganismo', 'cmb_organismo', Organismo::get_organismos(), 'idorganismo', 'descripcion', 'Organismo') ?>
        </div>

    </div>

    <div class="row">
        <div class=" col-md-4">
            <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
        </div>
        <div class=" col-md-2">
            <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
        </div>
        <div class=" col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'es_oficial')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->es_oficial]) ?>
        </div>
        <div class=" col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'es_organismo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->es_organismo]) ?>
        </div>
        <div class=" col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
        </div>
    </div>



</div>

<?php ActiveForm::end(); ?>

</div>