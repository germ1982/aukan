<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockDepositoEgreso */
?>
<div class="stock-deposito-egreso-view">
 
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
            'idusuario_carga',
            'idusuario_edicion',
            'id_dispositivo_destino',
        ],
    ]) ?>

</div>
