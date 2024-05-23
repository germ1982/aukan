<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_entrega */
?>
<div class="sds-reg-entrega-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idregistroentrega',
            'idregistro',
            'idarticulo',
            'cantidad',
        ],
    ]) ?>

</div>
