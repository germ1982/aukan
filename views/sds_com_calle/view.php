<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_calle */
?>
<div class="sds-com-calle-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idcalle',
            'descripcion',
            'activo',
        ],
    ]) ?>

</div>
