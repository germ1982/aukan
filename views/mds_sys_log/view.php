<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_sys_log */
?>
<div class="mds-sys-log-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idlog',
            'fecha_hora',
            'idusuario',
            'accion',
            'modulo',
            'datos:ntext',
            'id',
        ],
    ]) ?>

</div>
