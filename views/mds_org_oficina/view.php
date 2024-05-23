<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_oficina */
?>
<div class="mds-org-oficina-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cupo',
            [
                'attribute'=>'activo',
                'value'=>function($model){
                    if($model->activo){
                        return "Si";
                    }else{
                        return "No";
                    }
                },
            ],
        ],
    ]) ?>

</div>
