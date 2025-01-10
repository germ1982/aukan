<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ViewStockArticulosCantidades */
?>
<div class="view-stock-articulos-cantidades-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idarticulo',
            'rubro',
            'descripcion',
            'ingresado',
            'entregado',
            'disponible',
        ],
    ]) ?>

</div>
