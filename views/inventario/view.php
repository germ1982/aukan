<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */
?>
<div class="inventario-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idInventario',
            'idarticulo',
            'cantidad',
            'iddispositivo',
            'idempleado',
            'idestado',
            'activo',
            'observacion:ntext',
            
        ],
    ]) ?>

</div>
