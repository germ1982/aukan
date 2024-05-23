<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_movimiento_equipo */
?>
<div class="sds-bdc-movimiento-equipo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idmovimientoequipo',
            'idmovimiento',
            'idequipo',
        ],
    ]) ?>

</div>
