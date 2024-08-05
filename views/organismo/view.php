<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Organismo */
?>
<div class="organismo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idorganismo',
            'descripcion',
            'padre',
            'nivel',
            'activo',
            'abreviatura',
        ],
    ]) ?>

</div>
