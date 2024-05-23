<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_permiso */
?>
<div class="mds-seg-permiso-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idpermiso',
            'descripcion',
            'idrol',
            'iditem',
            'alta',
            'baja',
            'modifica',
            'ver',
        ],
    ]) ?>

</div>
