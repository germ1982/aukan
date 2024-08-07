<?php

use app\models\ConfiguracionTipo;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Configuracion */
?>
<div class="configuracion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_configuracion',
            [
                'attribute' => 'id_configuracion_tipo',
                'value' => function ($model) {
                    $tipo = ConfiguracionTipo::findOne($model->id_configuracion_tipo);
                    return $tipo->descripcion;
                },
            ],
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
