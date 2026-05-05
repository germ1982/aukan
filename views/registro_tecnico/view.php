<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RegistroTecnico */
?>
<div class="registro-tecnico-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idregistro',
            'fecha_solicitud',
            'idsolicitante',
            'iddispositivo',
            'idtipo_registro',
            'problema',
            'solucion',
            'fecha_solucion',
        ],
    ]) ?>

</div>
