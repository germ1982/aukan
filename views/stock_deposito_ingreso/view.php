<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockDepositoIngreso */
?>
<div class="stock-deposito-ingreso-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idingreso',
            'fecha',
            'idorigen',
            'origen_referencia',
            'idempleado_recepcion',
            'idusuario_carga',
            'observacion',
            'idusuario_edicion',
        ],
    ]) ?>

</div>
