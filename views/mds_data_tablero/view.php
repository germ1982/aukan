<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_data_tablero */
?>
<div class="mds-data-tablero-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idtablero',
            'nombre',
            'descripcion',
            'idcategoria',
            'url:url',
            'iditem',
            'orden',
            'estado'
        ],
    ]) ?>

</div>
