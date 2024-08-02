<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
?>
<div class="empleado-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idempleado',
            'idpersona',
            'iddispositivo',
            'legajo',
            'email:email',
            'telefono',
            'foto',
            'activo',
            'categoria',
            'antiguedad_legal',
            'antiguedad_total',
            'ingreso_real',
            'ingreso_administrativo',
            'contratacion',
            'cuil',
            'funcion',
            'fichado',
            'afiliacion',
        ],
    ]) ?>

</div>
