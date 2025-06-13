<?php

use app\controllers\SiteController;
use app\models\ConfiguracionTipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$array_tipos = ConfiguracionTipo::find()->where(['activo' => 1])->orderBy('descripcion')->all()
?>



<div class="configuracion-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form_configuracion',
    ]); ?>

    <div class="row">
        <?php if (empty($ocultarTipo)): ?>
            <div class="col-md-5">
                <?= SiteController::actionGet_input_select2($form, $model, 'id_configuracion_tipo', 'cmb_tipo', $array_tipos, 'id_configuracion_tipo', 'descripcion', 'Tipo de Dato', 'seleccione tipo...') ?>
            </div>
        <?php endif; ?>
        <div class="<?= empty($ocultarTipo) ? 'col-md-5' : 'col-md-10' ?>">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>