<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EdificioOficina */
?>
<div class="edificio-oficina-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idoficina',
            'descripcion',
            'idedificio',
            'plano_ubicacion',
            'activo',
        ],
    ]) ?>

</div>
