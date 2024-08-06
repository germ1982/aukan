<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Articulo */
?>
<div class="articulo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idarticulo',
            'descripcion',
            'idtipo',
            'idmarca',
            'modelo',
            'idrubro',
            'id_unidad_medida',
            'activo',
            'imagen',
        ],
    ]) ?>

</div>
