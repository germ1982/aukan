<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_deposito */
?>
<div class="sds-stk-deposito-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'iddeposito',
            'descripcion',
            'activo',
            'idorganismo',
        ],
    ]) ?>

</div>
