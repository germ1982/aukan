<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_padron */
?>
<div class="mds-org-padron-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idpadron',
            'mes',
            'anio',
            'legajo',
            'idunidadoperativa',
            'categoria',
            'apellido_nombre',
            'sexo',
            'dni',
            'cuil',
            'fecha_nacimiento',
            'fecha_ingreso',
            'antiguedad_administrativa',
            'antiguedad_privada',
            'antiguedad_total',
            'eventual',
        ],
    ]) ?>

</div>
