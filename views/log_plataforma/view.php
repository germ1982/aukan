<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LogPlataforma */
?>
<div class="log-plataforma-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idlog',
            'idusuario',
            'fecha',
            'hora',
            'modulo',
            'accion',
            'idregistro',
        ],
    ]) ?>

</div>
