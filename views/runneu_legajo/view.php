<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RunneuLegajo */
?>
<div class="runneu-legajo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'num_legajo',
            'dni',
            'archivo_adjunto',
        ],
    ]) ?>

</div>
