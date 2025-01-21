<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ViewStockDepositoArticulosCantidades */
?>
<div class="view-stock-deposito-articulos-cantidades-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idarticulo',
            'rubro',
            'descripcion:ntext',
            'ingresado',
            'entregado',
            'disponible',
        ],
    ]) ?>

</div>
