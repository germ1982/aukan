<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_barrio */
?>
<div class="sds-com-barrio-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idbarrio',
            'nombre',
            'idlocalidad',
            'activo',
        ],
    ]) ?>

</div>
