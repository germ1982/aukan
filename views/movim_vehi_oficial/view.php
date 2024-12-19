<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MovimVehiOficial */
?>
<div class="movim-vehi-oficial-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idmovimiento',
            'idvehiculo',            
            'chofer',
            'salida',
            'regreso',
            'finalidad_viaje',
            'fecha',
            'lugar',
            'hora',
            'kilometraje',
        ],
    ]) ?>

</div>
