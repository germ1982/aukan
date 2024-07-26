<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockArticulo */
?>
<div class="stock-articulo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idarticulo',
            'descripcion',
            'idtipo',
            'idmarca',
            'modelo',
            'idrubro',
            'id_unidad_medida',
            'activo',
            'imagen',
        ],
    ]) ?>

</div>
