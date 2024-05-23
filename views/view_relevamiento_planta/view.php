<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\View_relevamiento_planta */
?>
<div class="view-relevamiento-planta-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'relevado',
            'ultima_modificacion',
            'apellido',
            'nombre',
            'documento',
            'legajo',
            'Cuil',
            'mail',
            'telefono',
            'organismo_funciones_actualmente',
            'Categoría',
            'lugar_planta_permanente',
            'fecha_ingreso',
            'fecha_nacimiento',
            'funcion_actual',
            'observaciones:ntext',
        ],
    ]) ?>

</div>
