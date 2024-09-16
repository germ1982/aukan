<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Localidades */
?>
<div class="localidades-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_provincia',
            'localidad',
            'codigo_postal',
            'activo',
        ],
    ]) ?>

</div>
