<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_inventario */
?>
<div class="sds-stk-inventario-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idinventario',
            'fecha_hora',
            'idusuario',
            'iddeposito',
            'idorganismo',
        ],
    ]) ?>

</div>
