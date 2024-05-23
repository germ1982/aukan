<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_factura */
?>
<div class="sds-cel-factura-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idfactura',
            'periodo_mes',
            'periodo_anio',
            'fecha_carga',
            'cuenta',
            'observaciones:ntext',
        ],
    ]) ?>

</div>
