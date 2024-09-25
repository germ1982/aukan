<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockInformaticaEgresoDetalle */
?>
<div class="stock-informatica-egreso-detalle-view">
 
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
