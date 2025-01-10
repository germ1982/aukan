<?php

use app\controllers\SiteController;
use app\models\Articulo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StockInformaticaEgresoDetalle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-informatica-egreso-detalle-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-10">
            <?= SiteController::actionGet_input_select2($form, $model, 'idarticulo', 'cmb_articulos', Articulo::get_articulos_rubro_disponible(115), 'idarticulo', 'descripcion', 'Articulo',null,null,'get_disponible_articulo();') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cantidad')->textInput(['id' => 'input_cantidad','onblur' => 'validarCantidad()',]) ?>
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

<script>

    let cantidad_maxima = 0;

    function get_disponible_articulo(){
        const id_articulo = $('#cmb_articulos').val();
        $.post("index.php?r=stock_informatica_egreso_detalle/disponible_articulo&idarticulo=" + id_articulo, function(data) {
            data = $.parseJSON(data);

            cantidad_maxima = data;
            $('#input_cantidad').val(null);

        });
    }


</script>
