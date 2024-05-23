<?php

use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_visita_equipo */
?>
<div class="sds-bdc-visita-equipo-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'idequipo',
                'value'=>function($model){
                    return "#".str_pad($model->idequipo,6,"0", STR_PAD_LEFT);
                }
            ],
            'ip',
            'responsable',
            'observaciones:ntext',
        ],
    ]) ?>

</div>
