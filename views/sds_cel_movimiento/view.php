<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_movimiento */
?>
<div class="sds-cel-movimiento-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idmovimiento',
            'linea',
            'numero',
            'organismo',
            'observaciones:ntext',
            'baja',
            'fecha',
        ],
    ]) ?>

</div>
