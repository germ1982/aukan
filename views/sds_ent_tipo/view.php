<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_tipo */
?>
<div class="sds-ent-tipo-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idtipo',
            'descripcion',
            [
                'attribute' => 'activo',
                'value' => function ($model) {
                    return $model->activo ? 'Si' : 'No';
                }
            ],
            [
                'attribute' => 'tiene_numero',
                'value' => function ($model) {
                    return $model->tiene_numero ? 'Si' : 'No';
                }
            ]
        ],
    ]) ?>

</div>