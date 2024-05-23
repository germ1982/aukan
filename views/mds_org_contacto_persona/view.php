<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_contacto_persona */
?>
<div class="mds-org-contacto-persona-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'legajo',
            'dni',
            'apellido',
            'nombre',
            'domicilio',
            'localidad',
            'in_prov',
        ],
    ]) ?>

</div>
