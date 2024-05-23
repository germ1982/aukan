<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_atp_sucursal */
?>
<div class="mds-atp-sucursal-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idsucursal',
            'codigo',
            'direccion',
        ],
    ]) ?>

</div>
