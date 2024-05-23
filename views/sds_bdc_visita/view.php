<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_visita */
?>
<div class="sds-bdc-visita-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'fecha',
                'value' => function($model){
                    return date('d/m/Y', strtotime($model->fecha));
                }
            ],
            'sector',
            'observacion:ntext',
        ],
    ]) ?>

</div>
