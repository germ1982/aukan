<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InformaticaWebEventos */
?>
<div class="informatica-web-eventos-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idevento',
            'fecha',
            'titulo',
            'descripcion:ntext',
            'fotos',
            'iddispositivo',
            'activo',
        ],
    ]) ?>

</div>
