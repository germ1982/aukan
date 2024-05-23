<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_orden_compra_item */
?>
<div class="sds-stk-orden-compra-item-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idordencompraitem',
            'idordencompra',
            'cantidad',
            'importe_unitario',
        ],
    ]) ?>

</div>
