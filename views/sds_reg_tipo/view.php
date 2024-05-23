<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_tipo */
?>
<div class="sds-reg-tipo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'descripcion',
            'activo'=>[
                'label'=>'Activo',
                'value'=>($model->activo==1?'Si': 'No')
            ],
        ],
    ]) ?>

</div>
