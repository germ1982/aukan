<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_veh_habilitacion */
?>
<div class="sds-veh-habilitacion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idhabilitacion',
            'detalle:ntext',
            'vencimiento',
            'adjunto:ntext',
            'tipo',
            'idvehiculo',
        ],
    ]) ?>

</div>
