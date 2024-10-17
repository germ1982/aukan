<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Vehiculos */
?>
<div class="vehiculos-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idvehiculo',
            'idempleado',
            'idpersona',
            'dominio',
            'idmarca',
            'modelo',
            'color',
            'vehiculo_oficial',
        ],
    ]) ?>

</div>
