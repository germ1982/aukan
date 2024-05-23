<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_plan */
?>
<div class="sds-cel-plan-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idplan',
            'descripcion',
            ['attribute'=>'activo',
            'value'=>function($model){return $model->activo ? 'Si':'No';}],
        ],
    ]) ?>

</div>
