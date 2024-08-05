<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Configuracion */
?>
<div class="configuracion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_configuracion',
            'id_configuracion_tipo',
            'descripcion',
            'activo',
        ],
    ]) ?>

</div>
