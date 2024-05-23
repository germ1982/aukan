<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_feriado */
?>
<div class="mds-hor-feriado-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'idferiado',
                'label' => 'Id',
            ],
            [
                'attribute' => 'fecha',
                'value' => function ($model) {
                    $fecha = $model->fecha;
                    $anio = substr($fecha, 0, 4);
                    $mes  = substr($fecha, 5, 2);
                    $dia = substr($fecha, 8, 2);
                    $fecha = "$dia/$mes/$anio";
                    return $fecha;
                },
            ],
            [
                'attribute' => 'descripcion',

            ],

        ],
    ]) ?>

</div>
