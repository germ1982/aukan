<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EdificioConectividad */
?>
<div class="edificio-conectividad-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idconectividad',
            'idedificio',
            'infraestructura',
            'servicio',
            'velocidad_en_mb',
            'estado',
            'observacion',
            'tipo_conexion',
        ],
    ]) ?>

</div>
