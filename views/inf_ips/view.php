<?php

use app\models\Empleado;
use app\models\Persona;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InfIps */
?>
<div class="inf-ips-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idip',
            'ip',
            [
                'attribute' => 'idempleado',
                'value' => function ($model) {
                    $empleado = Empleado::findOne($model->idempleado);
                    $persona = Persona::findOne($empleado->idpersona);
                    return "$persona->apellido $persona->nombre";
                },
            ],
        ],
    ]) ?>

</div>