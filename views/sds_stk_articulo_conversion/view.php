<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $models app\models\Sds_stk_articulo_conversion */
?>
<div class="sds-stk-articulo-conversion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idarticuloconversion',
            'articulo_base',
            'articulo_convertido',
        ],
    ]) ?>

</div>
