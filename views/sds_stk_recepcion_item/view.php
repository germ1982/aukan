<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_recepcion_item */
?>
<div class="sds-stk-recepcion-item-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idrecepcionitem',
            'idrecepcion',
            'idarticulo',
            'descripcion',
        ],
    ]) ?>

</div>
