<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_movimiento */
?>
<div class="sds-bdc-movimiento-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idmovimiento',
            'fecha_hora',
            'idusuario',
            'solicitante',
            'tipo',
            'responsable_anterior',
            'responsable_nuevo',
            'usuario_anterior',
            'usuario_nuevo',
            'ip_anterior',
            'ip_nueva',
            'observaciones:ntext',
        ],
    ]) ?>

</div>
