<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\mds_por_desempleo */
?>
<div class="mds-por-desempleo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'iddesempleo',
            'tipo',
            'fecha',
            'dni',
            'nombre',
            'cheque',
            'monto',
            'prov',
            'lug',
        ],
    ]) ?>

</div>
