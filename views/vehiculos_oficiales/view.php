<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\VehiculosOficiales */
?>
<div class="vehiculos-oficiales-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idvehiculo',
            'dominio',
            'poliza',
            'VTO',
            'salida',
            'llegada',
            'lugar',
            'hora',
            'kilometraje',
            'finalidad_viaje',
        ],
    ]) ?>

</div>
