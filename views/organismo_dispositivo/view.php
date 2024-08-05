<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDispositivo */
?>
<div class="organismo-dispositivo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'iddispositivo',
            'descripcion',
            'idorganismo',
            'es_oficial',
            'es_organismo',
            'activo',
            'direccion',
            'alias',
            'idcapaitem',
            'telefono',
        ],
    ]) ?>

</div>
