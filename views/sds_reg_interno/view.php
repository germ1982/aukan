<?php
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_interno */
?>
<div class="sds-reg-interno-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idinterno',
            'edificio',
            'organismo',
            'dispositivo',
            'grupo',
            'responsable',
            [
                'attribute'=>'recepcion',
                'value'=>function($model){
                    if($model->recepcion==1){
                        return 'Si';
                    }else{
                        return 'No';
                    }
                }
            ],
        ],
    ]) ?>

</div>
