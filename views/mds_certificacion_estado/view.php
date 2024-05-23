<?php

use yii\widgets\DetailView;

?>
<div class="mds_certificacion_estado-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idcertificacionestado',
            'descripcion',
            'activo',
        ],
    ]) ?>

</div>