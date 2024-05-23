<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_factura_item */
?>
<div class="sds-cel-factura-item-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idfacturaitem',
            'idfactura',
            'linea',
            'concepto',
            'cantidad',
            'neto',
            'impuestos',
            'total',
            'idconcepto',
        ],
    ]) ?>

</div>
