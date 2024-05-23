<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_intervencion_agresor */
?>
<div class="sds-vio-intervencion-agresor-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idintervencion',
            'idagresor',
            'parentezco',
        ],
    ]) ?>

</div>
