<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockInformaticaIngreso */
?>
<div class="stock-informatica-ingreso-view">
 
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
        ],
    ]) ?>

</div>
