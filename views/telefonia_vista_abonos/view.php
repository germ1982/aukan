<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Telefonia_vista_abono */
?>
<div class="telefonia-vista-abono-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'lineanro',
            'ultimo_movimiento',
            'externos',
            'abonodato',
            'movimientos',
        ],
    ]) ?>

</div>
