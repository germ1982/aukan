<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\View_stock_deposito */
?>
<div class="view-stock-deposito-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idarticulo',
            'deposito',
            'organismo',
            'deposito_descripcion',
            'stock',
        ],
    ]) ?>

</div>
