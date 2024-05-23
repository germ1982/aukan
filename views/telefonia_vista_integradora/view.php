<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Telefonia_vista_integradora */
?>
<div class="telefonia-vista-integradora-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'lineanro',
            'cuenta',
            'empresa',
            'ultimo_movimiento',
            'organismo',
            'dependecia',
            'responsable',
            'equipo',
            'imei',
            'plan',
        ],
    ]) ?>

</div>
