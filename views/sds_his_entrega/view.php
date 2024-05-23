<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_his_entrega */
?>
<div class="sds-his-entrega-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'numero_documento',
            'fecha',
            'servicio',
            'cantidad',
            'destino',
        ],
    ]) ?>

</div>
