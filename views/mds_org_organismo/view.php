<?php

use app\models\Mds_org_organismo;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_organismo */
?>
<div class="mds-org-organismo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'descripcion',
            ['attribute'=>'padre',
            'value'=>function($model){
                if ($model->padre!=null){
                    return Mds_org_organismo::findOne(["idorganismo"=>$model->padre])->descripcion;
                }
                return "";
            }],
            'abreviatura',
            'nivel',
            ['attribute'=>'activo',
            'value'=>function($model){return $model->activo ? 'Si':'No';}],
        ],
    ]) ?>

</div>
