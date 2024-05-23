<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_entrega_item */
?>
<div class="sds-stk-entrega-item-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'identregaitem',
            'recepcion_item',
            'cantidad',
            'identrega',
        ],
    ]) ?>

</div>
