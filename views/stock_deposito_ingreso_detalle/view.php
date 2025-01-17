<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockDepositoIngresoDetalle */
?>
<div class="stock-deposito-ingreso-detalle-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'iddetalle',
            'idingreso',
            'idarticulo',
            'cantidad',
        ],
    ]) ?>

</div>
