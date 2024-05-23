<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Telefonia_vista_linea */
?>
<div class="telefonia-vista-linea-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'lineanro',
            'ultimo_movimiento',
            'cuenta',
            'simcard',
            'empresa',
            'movimientos',
        ],
    ]) ?>

</div>
