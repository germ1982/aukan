<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_situacion */
?>
<div class="mds-org-situacion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idsituacion',
            'idcontacto',
            'idcapaitem',
            'inicio',
            'fin',
            'descripcion',
            'profesional_firma',
            'dias_horarios',
            'iddocumento',
        ],
    ]) ?>

</div>
