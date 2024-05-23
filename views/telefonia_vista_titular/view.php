<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Telefonia_vista_titular */
?>
<div class="telefonia-vista-titular-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'lineanro',
            'ultimo_movimiento',
            'organismo',
            'dependencia',
            'responsable',
            'movimientos',
        ],
    ]) ?>

</div>
