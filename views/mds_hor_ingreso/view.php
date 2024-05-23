<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_ingreso */
?>
<div class="mds-hor-ingreso-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idingreso',
            'idcontacto',
            'fecha_hora',
            'temperatura',
            'observaciones:ntext',
        ],
    ]) ?>

</div>
