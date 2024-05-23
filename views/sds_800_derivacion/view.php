<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_derivacion */
?>
<div class="sds-800-derivacion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idderivacion',
            'descripcion',
            'direccion',
            'telefonos',
            'activo',
        ],
    ]) ?>

</div>
