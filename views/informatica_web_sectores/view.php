<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InformaticaWebSectores */
?>
<div class="informatica-web-sectores-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idsector',
            'nombre',
            'descripcion',
            'fotos',
            'activo',
            'orden',
        ],
    ]) ?>

</div>
