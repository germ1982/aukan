<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InformaticaWebEmpleados */
?>
<div class="informatica-web-empleados-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idwebempleado',
            'idempleado',
            'descripcion:ntext',
            'activo',
            'orden',
        ],
    ]) ?>

</div>
