<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Backend */
?>
<div class="backend-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idbackend',
            'nombre',
            'descripcion:ntext',
            'endpoint',
            'estado',
        ],
    ]) ?>

</div>
