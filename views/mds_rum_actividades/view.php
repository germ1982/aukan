<?php

use app\models\Sds_com_configuracion_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_configuracion */
?>
<div class="sds-com-configuracion-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'descripcion',
            [
                'attribute' => 'activo',
                'value' => function ($model) {
                    return $model->activo == 1 ? 'Si' : 'No';
                },
            ],
        ],
    ]) ?>

</div>