<?php

use app\controllers\SiteController;
use app\models\Articulo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="stock-informatica-ingreso-detalle-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-10">
            <?= SiteController::actionGet_input_select2($form, $model, 'idarticulo', 'cmb_articulos', Articulo::get_articulos_rubro(115), 'idarticulo', 'descripcion', 'Articulo') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cantidad')->textInput() ?>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <?= Html::button('Cancelar', [
                'class' => 'btn btn-default',
                'onclick' => 'ocultar_abm_item();' // Oculta el formulario
            ]) ?>
        </div>
        <div class="col-md-6 text-right">
            <?= Html::button('Guardar', [
                'class' => 'btn btn-primary',
                'onclick' => 'guardarDetalle()' // Función JavaScript que procesa el guardado
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>