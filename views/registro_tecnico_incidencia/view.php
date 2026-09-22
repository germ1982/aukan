<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RegistroTecnicoIncidencia */
?>
<div class="registro-tecnico-incidencia-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idincidencia',
            'idregistro',
            'idinventario',
            'idingresante',
            'idrecepciona',
            'iddispositivo',
            'idestado',
            'iddespacha',
            'idretira',
        ],
    ]) ?>

</div>
