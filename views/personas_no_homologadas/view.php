<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PersonasNoHomologadas */
?>
<div class="personas-no-homologadas-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idpersona_no_homologada',
            'documento',
            'documento_tipo',
            'nacionalidad',
            'genero',
            'fecha_nacimiento',
            'nombre',
            'apellido',
        ],
    ]) ?>

</div>
