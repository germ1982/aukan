<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_usuario_capa_item */
?>
<div class="mds-seg-usuario-capa-item-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idusuariocapaitem',
            'idusuario',
            'idcapaitem',
        ],
    ]) ?>

</div>
