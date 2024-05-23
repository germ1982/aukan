<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_inventario_item */
?>
<div class="sds-stk-inventario-item-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idinventarioitem',
            'idinventario',
            'idarticulo',
            'cantidad',
        ],
    ]) ?>

</div>
