<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDecreto */
?>
<div class="organismo-decreto-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'iddecreto',
            'descripcion',
            'periodo_inicio',
            'periodo_final',
            'periodo_prorroga',
            'activo',
        ],
    ]) ?>

</div>
