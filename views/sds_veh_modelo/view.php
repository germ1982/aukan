<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_veh_modelo */
?>
<div class="sds-veh-modelo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idmodelo',
            'descripcion',
            'idmarca',
            'activo',
        ],
    ]) ?>

</div>
