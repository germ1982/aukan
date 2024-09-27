<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Edificio */
?>
<div class="edificio-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idedificio',
            'descripcion_fija',
            'descripcion_gestion',
            'idlocalidad',
            'direccion_calle',
            'direccion_altura',
            'direccion',
            'geolocalizacion:ntext',
            'activo',
        ],
    ]) ?>

</div>
