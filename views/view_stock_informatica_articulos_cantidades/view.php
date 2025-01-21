<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ViewStockInformaticaArticulosCantidades */
?>
<div class="view-stock-informatica-articulos-cantidades-view">
 
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
