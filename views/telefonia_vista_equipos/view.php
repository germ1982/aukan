<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Telefonia_vista_equipos */
?>
<div class="telefonia-vista-equipos-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'lineanro',
            'ultimo_movimiento',
            'modelo',
            'dispositivo',
            'imei',
            'movimientos',
        ],
    ]) ?>

</div>
