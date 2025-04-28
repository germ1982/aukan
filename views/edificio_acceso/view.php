<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EdificioAcceso */
?>
<div class="edificio-acceso-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_edificio_acceso',
            'idedificio',
            'descripcion',
        ],
    ]) ?>

</div>
