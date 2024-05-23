<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_remanente */
?>
<div class="mds-hor-remanente-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idremanente',
            'idcontacto',
            'anio',
            'dias',
        ],
    ]) ?>

</div>
