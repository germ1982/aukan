<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_his_admix */
?>
<div class="sds-his-admix-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'documento_numero',
            'nombre',
            'servicio',
            'importe',
            'fecha',
            'periodo',
            'extracto',
        ],
    ]) ?>

</div>
