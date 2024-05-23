<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_interior */
?>
<div class="sds-800-atencion-interior-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idllamada',
            'idpersona',
            'lugar_intervencion',
            'lugar_especificacion',
            'defensora',
            'idpersona_referente',
            'parentezco',
            'plan_accion:ntext',
            'fecha_intervencion',
            'idusuario',
            'archivo_adjunto:ntext',
        ],
    ]) ?>

</div>
