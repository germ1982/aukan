<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockDepositoEgresoDetalle */
?>
<div class="stock-deposito-egreso-detalle-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'iddetalle',
            'idegreso',
            'idarticulo',
            'cantidad',
        ],
    ]) ?>

</div>
