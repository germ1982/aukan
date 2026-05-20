<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionDiccionario */
?>
<div class="configuracion-diccionario-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idcorreccion',
            'palabra_mal',
            'palabra_correcta',
        ],
    ]) ?>

</div>
