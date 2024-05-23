<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_franco */
?>
<div class="mds-hor-franco-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idfranco',
            'idcontacto',
            'fecha',
            'descripcion',
        ],
    ]) ?>

</div>
