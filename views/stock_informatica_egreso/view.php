<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockInformaticaEgreso */
?>
<div class="stock-informatica-egreso-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idegreso',
            'fecha',
            'idpersona_solicitante',
            'idempleado_autorizacion',
            'idempleado_despacha',
            'idpersona_recibe',
            'observacion:ntext',
        ],
    ]) ?>

</div>
