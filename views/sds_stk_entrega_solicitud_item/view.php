<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_entrega_solicitud_item */
?>
<div class="sds-stk-entrega-solicitud-item-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'identregasolicituditem',
            'idarticulo',
            'cantidad',
            'identregasolicitud',
        ],
    ]) ?>

</div>
