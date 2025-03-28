<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RegistroRecepcion */
?>
<div class="registro-recepcion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_registro_recepcion',
            'fecha',
            'hora',
            'dni',
            'motivo:ntext',
            'acceso',
            'id_dispositivo_derivacion',
            'id_responsable_derivacion',
            'id_tipo_recepcion',
            'observacion:ntext',
        ],
    ]) ?>

</div>
