<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_pad_padron */
?>
<div class="mds-pad-padron-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'idpadron',  
            'documento',
            'apellido',
            'nombre',
            'calle',
            'altura',
            'circuito_anterior',
            'circuito_nuevo',
            'denominacion_circuito',
            'afiliacion',
        ],
    ]) ?>

</div>
