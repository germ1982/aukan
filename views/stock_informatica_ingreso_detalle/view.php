<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockInformaticaIngresoDetalle */
?>
<div class="stock-informatica-ingreso-detalle-view">
 
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
