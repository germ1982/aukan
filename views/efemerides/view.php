<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Efemerides */
?>
<div class="efemerides-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idefemeride',
            'fecha',
            'titulo',
            'descripcion:ntext',
            'activo',
        ],
    ]) ?>

</div>
