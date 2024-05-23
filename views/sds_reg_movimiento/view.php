<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_movimiento */
?>
<div class="sds-reg-movimiento-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idmovimiento',
            'idregistro',
            'fecha',
            'descripcion:ntext',
            'idusuario',
            'idtecnico',
            'tipo',
        ],
    ]) ?>

</div>
