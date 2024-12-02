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
    <?php ActiveForm::end(); ?>
    
</div>
