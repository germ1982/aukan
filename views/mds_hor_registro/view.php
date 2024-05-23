<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_registro */
?>
<div class="mds-hor-registro-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'contacto',
            [
                'attribute' => 'fecha',
                'value' => function($model){
                    return $model->fecha!=null?date('d/m/Y H:i:s', strtotime($model->fecha)):'-';
                }
            ],
            [
                'attribute' => 'origen',
                'value' => function ($model) {
                    if($model->origen==0){
                        return "Importado ".($model->usuario_carga!=null? "($model->usuario_carga - Fecha: $model->fecha_carga)":"");
                    }elseif($model->origen==1){
                        return 'Carga Manual '.($model->usuario_carga!=null? "($model->usuario_carga - Fecha: ".date('d/m/Y H:i',strtotime($model->fecha_carga))."hs)":"");
                    }else{
                        return 'App Ciclo '.($model->usuario_carga!=null? "($model->usuario_carga - Fecha: $model->fecha_carga)":"");
                    }
                }
           ],
           [
            'attribute' => 'observaciones',
            'value' => function($model){
                return $model->observaciones!=null?$model->observaciones:'-';
            }
           ]
        ],
    ]) ?>

</div>
